<?php

namespace App\Http\Livewire\Sales;

use App\Models\Sale;
use App\Models\Client;
use App\Models\Product;
use Livewire\Component;
use App\Models\Supplier;
use App\Models\VoucherTypes;
use Livewire\WithFileUploads;
use App\Models\PaymentMethods;
use App\Models\PaymentConditions;
use App\Models\ProductSaleOrder;
use App\Models\SaleOrder;

class CreateSale extends Component
{

    use WithFileUploads;

    // SALE PARAMETERS
    public $clients = [], $payment_conditions = [], $payment_methods = [], $voucher_types = [];
    public $client_iva_condition = '', $client_discriminates_iva, $has_order_associated = 0, $client_orders = [];

    // PRODUCTS
    public $orderProducts = [];
    public $allProducts = [];

    // CREATE FORM
    public $createForm = [
        'user_id' => 1,
        'date' => '',
        'client_id' => '',
        'client_order_id' => 0,
        'payment_condition_id' => '',
        'payment_method_id' => '',
        'voucher_type_id' => '',
        'voucher_number' => '',
        'subtotal' => 0,
        'iva' => 0,
        'total' => 0,
        'observations' => '',
    ];

    // VALIDATION
    protected $rules = [
        'createForm.date' => 'required|date',
        'createForm.client_id' => 'required|integer|exists:clients,id',
        'createForm.client_order_id' => 'nullable|integer',
        'createForm.payment_condition_id' => 'required|integer|exists:payment_conditions,id',
        'createForm.payment_method_id' => 'required|integer|exists:payment_methods,id',
        'createForm.voucher_type_id' => 'required|integer|exists:voucher_types,id',
        'createForm.voucher_number' => 'required|numeric|unique:sales,voucher_number',
        'createForm.subtotal' => 'required',
        'createForm.iva' => 'required',
        'createForm.total' => 'required',
        'createForm.observations' => 'nullable|string',
        'orderProducts.*.product_id' => 'required',
        'orderProducts.*.quantity' => 'required|numeric|min:1',
    ];

    public function mount()
    {
        $this->clients = Client::orderBy('business_name')->get();
        $this->payment_conditions = PaymentConditions::orderBy('name')->get();
        $this->payment_methods = PaymentMethods::orderBy('name')->get();
        $this->voucher_types = VoucherTypes::orderBy('name')->get();

        $this->allProducts = Product::where('is_salable', true)->orderBy('name')->get();
        $this->orderProducts = [
            ['product_id' => '', 'quantity' => 1, 'price' => 0, 'subtotal' => '0']
        ];
    }

    // RESET ORDER PRODUCTS
    public function resetOrderProducts()
    {
        $this->orderProducts = [
            ['product_id' => '', 'quantity' => 1, 'price' => 0, 'subtotal' => '0']
        ];
        $this->createForm['subtotal'] = 0;
        $this->createForm['iva'] = 0;
        $this->createForm['total'] = 0;
    }

    public function updatedCreateFormClientId()
    {
        $this->client_iva_condition = Client::find($this->createForm['client_id'])->iva_condition->name ?? '';
        $this->client_discriminates_iva = Client::find($this->createForm['client_id'])->iva_condition->discriminate ?? null;
        $this->reset(['has_order_associated', 'client_orders']);
        $this->resetOrderProducts();
        // $this->updatedOrderProducts();
    }

    public function updatedHasOrderAssociated()
    {
        if ($this->has_order_associated == 0) {
            $this->client_orders = [];
            $this->resetOrderProducts();
        } else {
            $this->client_orders = SaleOrder::where('client_id', $this->createForm['client_id'])->where('is_active', true)->get();
        }
    }

    // CLIENT ORDERS
    public function updatedCreateFormClientOrderId($value)
    {
        $this->orderProducts = [];
        $this->orderProducts = ProductSaleOrder::where('sale_order_id', $value)->get()->toArray();
        $this->updatedOrderProducts();
    }

    // ADD PRODUCT
    public function addProduct()
    {
        if (count($this->orderProducts) == count($this->allProducts)) {
            return;
        }

        if (!empty($this->orderProducts[count($this->orderProducts) - 1]['product_id']) || count($this->orderProducts) == 0) {
            $this->orderProducts[] = ['product_id' => '', 'quantity' => 1, 'price' => 0, 'subtotal' => '0'];
        }
    }

    // IS PRODUCT IN ORDER
    public function isProductInOrder($product_id)
    {
        foreach ($this->orderProducts as $product) {
            if ($product['product_id'] == $product_id) {
                return true;
            }
        }
        return false;
    }

    // REMOVE PRODUCT
    public function removeProduct($index)
    {
        unset($this->orderProducts[$index]);
        $this->orderProducts = array_values($this->orderProducts);
        $this->updatedOrderProducts();
    }

    // SHOW PRODUCTS
    public function showProducts()
    {
        dd($this->orderProducts);
    }

    // UPDATED ORDER PRODUCTS
    public function updatedOrderProducts()
    {
        $subtotal = 0;

        if ($this->client_discriminates_iva) {
            foreach ($this->orderProducts as $index => $product) {
                // Si el cliente discrimina IVA, se calcula el IVA sobre el subtotal de la venta
                $this->orderProducts[$index]['price'] = Product::find($product['product_id'])->selling_price ?? 0;
                $this->orderProducts[$index]['subtotal'] = number_format($product['quantity'] * $this->orderProducts[$index]['price'], 2, '.', '');
                $subtotal += $this->orderProducts[$index]['subtotal'];
            }
        } else {
            foreach ($this->orderProducts as $index => $product) {
                // Si el cliente no discrimina IVA, se calcula el IVA junto al precio unitario de cada producto
                $aux = Product::find($product['product_id'])->selling_price ?? 0;
                $aux *= 1.21;
                $this->orderProducts[$index]['price'] = $aux;
                $this->orderProducts[$index]['subtotal'] = number_format($product['quantity'] * $this->orderProducts[$index]['price'], 2, '.', '');
                $subtotal += $this->orderProducts[$index]['subtotal'];
            }
        }

        // Resumen de la venta
        if ($this->client_discriminates_iva) {
            $this->createForm['subtotal'] = number_format($subtotal, 2, '.', '');
            $this->createForm['iva'] = number_format($subtotal * 0.21, 2, '.', '');
            $this->createForm['total'] = number_format($subtotal * 1.21, 2, '.', '');
        } else {
            $this->createForm['total'] = number_format($subtotal, 2, '.', '');
        }
    }

    // CREATE SALE
    public function save()
    {
        // Validamos los campos
        $this->validate();

        // Controlamos que exista stock para todos los productos de la orden, caso contrario, mostramos un mensaje de error
        foreach ($this->orderProducts as $orderProduct) {
            $product = Product::find($orderProduct['product_id']);
            if ($product->real_stock < $orderProduct['quantity']) {
                $this->emit('error', 'No hay stock suficiente para el producto ' . $product->name);
                return;
            }
        }

        // Creamos la venta con los datos del formulario
        $sale = Sale::create($this->createForm);

        // Creamos los productos de la venta en la tabla pivote
        if ($this->client_discriminates_iva) {
            foreach ($this->orderProducts as $product) {
                $sale->products()->attach($product['product_id'], [
                    'quantity' => $product['quantity'],
                    'price' => $product['price'],
                    'subtotal' => $product['quantity'] * $product['price'],
                ]);
            }
        } else {
            foreach ($this->orderProducts as $product) {
                $sale->products()->attach($product['product_id'], [
                    'quantity' => $product['quantity'],
                    'price' => $product['price'] / 1.21,
                    'subtotal' => $product['quantity'] * ($product['price'] / 1.21),
                ]);
            }
        }

        // Obtenemos el subtotal de la venta
        $subtotal = $sale->products->sum(function ($product) {
            return $product->pivot->subtotal;
        });

        // Obtenemos el IVA de la venta
        $iva = $subtotal * 0.21;

        $sale->update([
            'subtotal' => $subtotal,
            'iva' => $iva,
            'total' => $subtotal + $iva,
        ]);

        // Actualizamos el stock de los productos
        foreach ($sale->products as $product) {
            $p = Product::find($product->id);
            $p->update([
                'real_stock' => $p->real_stock - $product->pivot->quantity,
            ]);
        }

        // Actualizamos la orden de venta
        if ($this->createForm['client_order_id'] != null) {
            $saleOrder = SaleOrder::find($this->createForm['client_order_id']);
            $saleOrder->update([
                'its_done' => true,
            ]);
        }

        // Añadimos 1 venta al cliente
        $client = Client::find($sale->client_id);
        $client->update([
            'total_sales' => $client->total_sales + 1,
        ]);

        // Reseteamos el formulario
        $this->reset('createForm', 'orderProducts');

        // Retornamos mensaje de éxito y redireccionamos
        $id = $sale->id;
        $message = '¡La venta se ha creado correctamente! Su ID es: ' . $id;
        session()->flash('flash.banner', $message);
        return redirect()->route('admin.sales.index');
    }

    public function render()
    {
        return view('livewire.sales.create-sale');
    }
}
