<?php

namespace App\Http\Livewire\SaleOrders;

use App\Models\Client;
use App\Models\Product;
use Livewire\Component;
use App\Models\SaleOrder;

class CreateSaleOrder extends Component
{
    public $clients = [], $client_iva_condition = '', $client_discriminates_iva;

    // PRODUCTS
    public $orderProducts = [];
    public $allProducts = [];

    // CREATE FORM
    public $createForm = [
        'user_id' => 1,
        'client_id' => '',
        'registration_date' => '',
        'subtotal' => 0,
        'iva' => 0,
        'total' => 0,
        'observations' => '',
    ];

    // VALIDATION
    protected $rules = [
        'createForm.client_id' => 'required|integer|exists:clients,id',
        'createForm.registration_date' => 'required|date',
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
        $this->allProducts = Product::where('is_salable', true)->orderBy('name')->get();
        $this->orderProducts = [
            ['product_id' => '', 'quantity' => 1, 'price' => 0, 'subtotal' => '0']
        ];
    }

    public function updatedCreateFormClientId($value)
    {
        $client = Client::find($value);
        $this->client_iva_condition = $client->iva_condition->name;
        $this->client_discriminates_iva = $client->iva_condition->discriminate ? true : false;
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

    // IS PRODUCT ADDED
    public function isProductInOrder($productId)
    {
        foreach ($this->orderProducts as $product) {
            if ($product['product_id'] == $productId) {
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

    // CREATE SALE ORDER
    public function save()
    {
        $this->validate();

        // Creamos la orden de venta
        $saleOrder = SaleOrder::create($this->createForm);

        // Creamos los productos de la orden de venta en la tabla pivote
        if ($this->client_discriminates_iva) {
            foreach ($this->orderProducts as $product) {
                $saleOrder->products()->attach($product['product_id'], [
                    'quantity' => $product['quantity'],
                    'price' => $product['price'],
                    'subtotal' => $product['quantity'] * $product['price'],
                ]);
            }
        } else {
            foreach ($this->orderProducts as $product) {
                $saleOrder->products()->attach($product['product_id'], [
                    'quantity' => $product['quantity'],
                    'price' => $product['price'] / 1.21,
                    'subtotal' => $product['quantity'] * ($product['price'] / 1.21),
                ]);
            }
        }

        // Obtenemos el subtotal de la orden de venta
        $subtotal = $saleOrder->products->sum(function ($product) {
            return $product->pivot->subtotal;
        });

        // IVA
        $iva = $subtotal * 0.21;

        // Actualizamos el subtotal, el IVA y el total de la orden de venta
        $saleOrder->update([
            'subtotal' => $subtotal,
            'iva' => $iva,
            'total' => $subtotal + $iva,
        ]);

        // Retornamos mensaje de éxito y redireccionamos
        $id = $saleOrder->id;
        $message = '¡La orden venta se ha creado correctamente! Su ID es: ' . $id;
        session()->flash('flash.banner', $message);
        return redirect()->route('admin.sale-orders.index');
    }

    public function render()
    {
        return view('livewire.sale-orders.create-sale-order');
    }
}
