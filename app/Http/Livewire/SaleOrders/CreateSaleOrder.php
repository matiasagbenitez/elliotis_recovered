<?php

namespace App\Http\Livewire\SaleOrders;

use App\Models\Client;
use App\Models\Product;
use Livewire\Component;
use App\Models\SaleOrder;
use App\Http\Services\SaleOrderService;
use App\Http\Services\NecessaryProductionService;

class CreateSaleOrder extends Component
{
    public $clients = [], $client_iva_condition = '', $client_discriminates_iva = true;

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

    protected $validationAttributes = [
        'createForm.client_id' => 'cliente',
        'createForm.registration_date' => 'fecha de registro',
        'createForm.subtotal' => 'subtotal',
        'createForm.iva' => 'IVA',
        'createForm.total' => 'total',
        'createForm.observations' => 'observaciones',
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
        'orderProducts.*.m2_unitary' => 'required|numeric|min:0',
        'orderProducts.*.quantity' => 'required|numeric|min:1',
        'orderProducts.*.m2_total' => 'required|numeric|min:1',
        'orderProducts.*.m2_price' => 'required|numeric|min:0',
    ];

    public function mount()
    {
        $allClients = Client::orderBy('business_name')->get();
        $this->createForm['registration_date'] = date('Y-m-d');

        foreach ($allClients as $client) {
            $this->clients[] = [
                'id' => $client->id,
                'name' => $client->business_name,
                'iva_condition' => $client->iva_condition->name,
                'discriminates_iva' => $client->iva_condition->discriminate ? 'Discrimina' : 'No discrimina'
            ];
        }

        $this->allProducts = Product::where('is_salable', true)->orderBy('name')->get();
        $this->orderProducts = [
            ['product_id' => '', 'm2_unitary' => 0, 'quantity' => 1, 'm2_total' => 0, 'm2_price' => 0, 'subtotal' => 0]
        ];
    }

    public function updatedCreateFormClientId($value)
    {
        $client = Client::find($value);
        $this->client_iva_condition = $client->iva_condition->name;
        $this->client_discriminates_iva = $client->iva_condition->discriminate ? true : false;
        $this->orderProducts = [
            ['product_id' => '', 'm2_unitary' => 0, 'quantity' => 1, 'm2_total' => 0, 'm2_price' => 0, 'subtotal' => 0]
        ];
        $this->createForm = [
            'user_id' => 1,
            'client_id' => $value,
            'registration_date' => date('Y-m-d'),
            'subtotal' => 0,
            'iva' => 0,
            'total' => 0,
            'observations' => '',
        ];
    }

    // ADD PRODUCT
    public function addProduct()
    {
        if (count($this->orderProducts) == count($this->allProducts)) {
            return;
        }

        if (!empty($this->orderProducts[count($this->orderProducts) - 1]['product_id']) || count($this->orderProducts) == 0) {
            $this->orderProducts[] = ['product_id' => '', 'm2_unitary' => 0, 'quantity' => 1, 'm2_total' => 0, 'm2_price' => 0, 'subtotal' => 0];
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

    // CREATE SALE ORDER
    public function save()
    {
        try {
            $this->validate();

            // Creamos la orden de venta
            $saleOrder = SaleOrder::create($this->createForm);

            // Creamos los productos de la orden de venta en la tabla pivote
            foreach ($this->orderProducts as $product) {
                $saleOrder->products()->attach($product['product_id'], [
                    'm2_unitary' => $product['m2_unitary'],
                    'quantity' => $product['quantity'],
                    'm2_total' => $product['m2_total'],
                    'm2_price' => $this->client_discriminates_iva ? $product['m2_price'] : $product['m2_price'] / 1.21,
                    'subtotal' => $this->client_discriminates_iva ? $product['subtotal'] : $product['subtotal'] / 1.21,
                ]);
            }

            NecessaryProductionService::calculate(null, true);

            // Retornamos mensaje de éxito y redireccionamos
            $id = $saleOrder->id;
            $message = '¡La orden venta se ha creado correctamente! Su ID es: ' . $id;
            session()->flash('flash.banner', $message);
            return redirect()->route('admin.sale-orders.index');
        } catch (\Throwable $th) {
            $this->emit('error',  '¡Ha ocurrido un error! Verifica la información ingresada.');
        }
    }

    public function render()
    {
        return view('livewire.sale-orders.create-sale-order');
    }
}
