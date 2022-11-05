<?php

namespace App\Http\Livewire\PurchaseOrders;

use App\Models\Product;
use Livewire\Component;
use App\Models\Supplier;
use App\Models\PurchaseOrder;

class CreatePurchaseOrder extends Component
{
    public $suppliers = [], $supplier_iva_condition = '', $supplier_discriminates_iva;

    // PRODUCTS
    public $orderProducts = [];
    public $allProducts = [];

    // CREATE FORM
    public $createForm = [
        'user_id' => 1,
        'supplier_id' => '',
        'registration_date' => '',
        'subtotal' => 0,
        'iva' => 0,
        'total' => 0,
        'observations' => '',
    ];

    // VALIDATION
    protected $rules = [
        'createForm.supplier_id' => 'required|integer|exists:suppliers,id',
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
        $this->suppliers = Supplier::orderBy('business_name')->get();
        $this->allProducts = Product::where('is_buyable', true)->orderBy('name')->get();
        $this->orderProducts = [
            ['product_id' => '', 'quantity' => 1, 'price' => 0, 'subtotal' => '0']
        ];
    }

    public function updatedCreateFormSupplierId($value)
    {
        $supplier = Supplier::find($value);
        $this->supplier_iva_condition = $supplier->iva_condition->name;
        $this->supplier_discriminates_iva = $supplier->iva_condition->discriminate ? true : false;
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

        foreach ($this->orderProducts as $index => $product) {
            $this->orderProducts[$index]['subtotal'] = number_format($product['quantity'] * $product['price'], 2, '.', '');
            $subtotal += $this->orderProducts[$index]['subtotal'];
        }

        // Subtotal format to 2 decimals
        if ($this->supplier_discriminates_iva) {
            $this->createForm['subtotal'] = number_format($subtotal, 2, '.', '');
            $this->createForm['iva'] = number_format($subtotal * 0.21, 2, '.', '');
            $this->createForm['total'] = number_format($subtotal * 1.21, 2, '.', '');
        } else {
            $this->createForm['total'] = number_format($subtotal, 2, '.', '');
        }
    }

    // CREATE PURCHASE ORDER
    public function save()
    {
        $this->validate();

        $purchaseOrder = PurchaseOrder::create($this->createForm);

        foreach ($this->orderProducts as $product) {
            $purchaseOrder->products()->attach($product['product_id'], [
                'quantity' => $product['quantity'],
                'price' => $product['price'],
                'subtotal' => $product['quantity'] * $product['price']
            ]);
        }

        // Obtenemos el subtotal de la compra
        $subtotal = $purchaseOrder->products->sum(function ($product) {
            return $product->pivot->subtotal;
        });

        // IVA
        $iva = $subtotal * 0.21;

        // Actualizamos el subtotal, iva y total de la compra
        if ($this->supplier_discriminates_iva) {
            $purchaseOrder->subtotal = $subtotal;
            $purchaseOrder->iva = $iva;
            $purchaseOrder->total = $subtotal + $iva;
        } else {
            $purchaseOrder->subtotal = $subtotal;
            $purchaseOrder->iva = 0;
            $purchaseOrder->total = $subtotal;
        }

        // Retornamos a la vista de compras
        $id = $purchaseOrder->id;
        $message = '¡La orden de compra se ha creado correctamente! Su ID es: ' . $id;
        session()->flash('flash.banner', $message);
        return redirect()->route('admin.purchase-orders.index');
    }

    public function render()
    {
        return view('livewire.purchase-orders.create-purchase-order');
    }
}
