<?php

namespace App\Http\Livewire\Sales;

use App\Models\Sale;
use App\Models\Client;
use App\Models\Product;
use Livewire\Component;
use App\Models\Supplier;
use App\Models\SaleOrder;
use App\Models\VoucherTypes;
use Livewire\WithFileUploads;
use App\Models\PaymentMethods;
use App\Models\ProductSaleOrder;
use App\Models\PaymentConditions;
use Illuminate\Support\Facades\Auth;
use App\Http\Services\NecessaryProductionService;
use Termwind\Components\Dd;

class CreateSale extends Component
{

    use WithFileUploads;

    // SALE PARAMETERS
    public $clients = [], $payment_conditions = [], $payment_methods = [], $voucher_types = [];
    public $client_discriminates_iva, $has_order_associated = '', $client_orders = [];

    // PRODUCTS
    public $orderProducts = [];
    public $allProducts = [];

    // CREATE FORM
    public $createForm = [
        'user_id' => '',
        'date' => '',
        'client_id' => '',
        'client_order_id' => '',
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
        'createForm.client_id' => 'required|integer|exists:clients,id',
        'createForm.date' => 'required|date',
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
        'orderProducts.*.m2_unitary' => 'required|numeric|min:1',
        'orderProducts.*.quantity' => 'required|numeric|min:1',
        'orderProducts.*.m2_total' => 'required|numeric|min:1',
        'orderProducts.*.m2_price' => 'required|numeric|min:1',
    ];

    public function mount()
    {
        $allClients = Client::orderBy('business_name')->get();
        $this->createForm['date'] = date('Y-m-d');

        foreach ($allClients as $client) {
            $this->clients[] = [
                'id' => $client->id,
                'name' => $client->business_name,
                'iva_condition' => $client->iva_condition->name,
                'discriminates_iva' => $client->iva_condition->discriminate ? 'Discrimina' : 'No discrimina'
            ];
        }

        $this->payment_conditions = PaymentConditions::orderBy('name')->get();
        $this->payment_methods = PaymentMethods::orderBy('name')->get();
        $this->voucher_types = VoucherTypes::orderBy('name')->get();

        $this->allProducts = Product::where('is_salable', true)->orderBy('name')->get();
        $this->orderProducts = [
            ['product_id' => '', 'm2_unitary' => 0, 'quantity' => 1, 'm2_total' => 0, 'm2_price' => 0, 'subtotal' => 0]
        ];
    }

    // RESET ORDER PRODUCTS
    public function resetOrderProducts()
    {
        $this->orderProducts = [
            ['product_id' => '', 'm2_unitary' => 0, 'quantity' => 1, 'm2_total' => 0, 'm2_price' => 0, 'subtotal' => 0]
        ];
        $this->createForm['subtotal'] = 0;
        $this->createForm['iva'] = 0;
        $this->createForm['total'] = 0;
    }

    public function updatedCreateFormClientId($value)
    {
        $client = Client::find($value);
        $this->client_discriminates_iva = $client->iva_condition->discriminate ? true : false;
        $this->orderProducts = [
            ['product_id' => '', 'm2_unitary' => 0, 'quantity' => 1, 'm2_total' => 0, 'm2_price' => 0, 'subtotal' => 0]
        ];
        $this->createForm = [
            'user_id' => 1,
            'client_id' => $value,
            'date' => date('Y-m-d'),
            'subtotal' => 0,
            'iva' => 0,
            'total' => 0,
            'observations' => '',
        ];
        $this->reset('has_order_associated', 'client_orders');
    }

    public function updatedHasOrderAssociated()
    {
        if ($this->has_order_associated == 0) {
            $this->reset('client_orders');
            $this->resetOrderProducts();
        } else {
            $this->reset('client_orders');
            $this->client_orders = SaleOrder::where('client_id', $this->createForm['client_id'])->where('is_active', true)->get();
        }
    }

    // CLIENT ORDERS
    public function updatedCreateFormClientOrderId($value)
    {
        $this->orderProducts = [];

        $orderDetail = ProductSaleOrder::where('sale_order_id', $value)->get()->toArray();

        foreach ($orderDetail as $product) {
            $this->orderProducts[] = [
                'product_id' => $product['product_id'],
                'm2_unitary' => $product['m2_unitary'],
                'quantity' => $product['quantity'],
                'm2_total' => $product['m2_total'],
                'm2_price' => $this->client_discriminates_iva ? $product['m2_price'] : $product['m2_price'] * 1.21,
                'subtotal' => $this->client_discriminates_iva ? $product['subtotal'] : $product['subtotal'] * 1.21,
            ];
        }

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

        foreach ($this->orderProducts as $index => $product) {
            if (empty($product['quantity']) || empty($product['m2_price'])) {
                $this->orderProducts[$index]['subtotal'] = 0;
                continue;
            } else {
                $this->orderProducts[$index]['m2_unitary'] = Product::find($product['product_id'])->m2 ?? 0;
                $this->orderProducts[$index]['m2_total'] = number_format($this->orderProducts[$index]['quantity'] * $this->orderProducts[$index]['m2_unitary'], 2, '.', '');
                $this->orderProducts[$index]['subtotal'] = number_format($this->orderProducts[$index]['m2_total'] * $this->orderProducts[$index]['m2_price'], 2, '.', '');
                $subtotal += $this->orderProducts[$index]['subtotal'];
            }
        }

        // Resumen de la venta
        if ($this->client_discriminates_iva) {
            $this->createForm['subtotal'] = number_format($subtotal, 2, '.', '');
            $this->createForm['iva'] = number_format($subtotal * 0.21, 2, '.', '');
            $this->createForm['total'] = number_format($subtotal * 1.21, 2, '.', '');
        } else {
            $this->createForm['subtotal'] = number_format($subtotal / 1.21, 2, '.', '');
            $this->createForm['iva'] = number_format($subtotal / 1.21 * 0.21, 2, '.', '');
            $this->createForm['total'] = number_format($subtotal, 2, '.', '');
        }
    }

    public function updatePrice($index)
    {
        $price = Product::find($this->orderProducts[$index]['product_id'])->m2_price ?? 0;

        if (!$this->client_discriminates_iva) {
            $price = $price * 1.21;
        }

        $this->orderProducts[$index]['m2_price'] = number_format($price, 2, '.', '');
        $this->updatedOrderProducts();
    }


    // CREATE SALE
    public function save()
    {
        try {
            if ($this->has_order_associated == '' || $this->has_order_associated == 0) {
                $this->createForm['client_order_id'] = null;
            } elseif ($this->has_order_associated == 1 && $this->createForm['client_order_id'] == '') {
                $this->createForm['client_order_id'] = null;
            }
        } catch (\Throwable $th) {
            $this->emit('error', 'Ocurrió un error al intentar guardar la venta con la orden de venta seleccionada.');
        }

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

        $this->createForm['user_id'] = Auth::user()->id;

        try {
            // Creamos la venta con los datos del formulario
            $sale = Sale::create($this->createForm);

            // Creamos los productos de la venta en la tabla pivote
            foreach ($this->orderProducts as $product) {
                $sale->products()->attach($product['product_id'], [
                    'm2_unitary' => $product['m2_unitary'],
                    'quantity' => $product['quantity'],
                    'm2_total' => $product['m2_total'],
                    'm2_price' => $this->client_discriminates_iva ? $product['m2_price'] : $product['m2_price'] / 1.21,
                    'subtotal' => $this->client_discriminates_iva ? $product['subtotal'] : $product['subtotal'] / 1.21,
                ]);
            }

            // Actualizamos el stock de los productos
            foreach ($sale->products as $product) {
                $p = Product::find($product->id);
                $p->update([
                    'real_stock' => $p->real_stock - $product->pivot->quantity,
                ]);
            }

            // Actualizamos la orden de venta
            if ($this->has_order_associated) {
                $saleOrder = SaleOrder::find($this->createForm['client_order_id']);
                if ($saleOrder) {
                    $saleOrder->update([
                        'its_done' => true,
                    ]);
                }
            }

            // Añadimos 1 venta al cliente
            $client = Client::find($sale->client_id);
            $client->update([
                'total_sales' => $client->total_sales + 1,
            ]);

            NecessaryProductionService::calculate(null, true);

            // Retornamos mensaje de éxito y redireccionamos
            $id = $sale->id;
            $message = '¡La venta se ha creado correctamente! Su ID es: ' . $id;
            session()->flash('flash.banner', $message);
            return redirect()->route('admin.sales.index');
        } catch (\Throwable $th) {
            dd($th->getMessage());
            // $this->emit('error', '¡Ha ocurrido un error! Verifica la información ingresada.');
        }
    }

    public function render()
    {
        return view('livewire.sales.create-sale');
    }
}
