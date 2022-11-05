<?php

namespace App\Http\Livewire\Purchases;

use App\Models\Price;
use App\Models\Product;
use Livewire\Component;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\VoucherTypes;
use App\Models\PurchaseOrder;
use Livewire\WithFileUploads;
use Ramsey\Uuid\Type\Decimal;
use App\Models\PaymentMethods;
use App\Models\PaymentConditions;
use App\Models\ProductPurchaseOrder;

class CreatePurchase extends Component
{
    use WithFileUploads;

    // PURCHASE PARAMETERS
    public $payment_conditions = [], $payment_methods = [], $voucher_types = [], $suppliers = [];
    public $supplier_iva_condition = '', $supplier_discriminates_iva, $has_order_associated = 0, $supplier_orders = [];

    protected $listeners = ['save' => 'save'];

    // PRODUCTS
    public $orderProducts = [];
    public $allProducts = [];

    // CREATE FORM
    public $createForm = [
        'user_id' => 1,
        'date' => '',
        'supplier_id' => '',
        'supplier_order_id' => 0,
        'payment_condition_id' => '',
        'payment_method_id' => '',
        'voucher_type_id' => '',
        'voucher_number' => '',
        'subtotal' => 0,
        'iva' => 0,
        'total' => 0,
        'weight' => 0,
        'weight_voucher' => '',
        'observations' => '',
    ];

    // VALIDATION
    protected $rules = [
        'createForm.date' => 'required|date',
        'createForm.supplier_id' => 'required|integer|exists:suppliers,id',
        'createForm.supplier_order_id' => 'nullable|integer',
        'createForm.payment_condition_id' => 'required|integer|exists:payment_conditions,id',
        'createForm.payment_method_id' => 'required|integer|exists:payment_methods,id',
        'createForm.voucher_type_id' => 'required|integer|exists:voucher_types,id',
        'createForm.voucher_number' => 'required|numeric|unique:purchases,voucher_number',
        'createForm.subtotal' => 'required',
        'createForm.iva' => 'required',
        'createForm.total' => 'required',
        'createForm.weight' => 'nullable|numeric',
        'createForm.weight_voucher' => 'nullable|image|max:1024',
        'createForm.observations' => 'nullable|string',
        'orderProducts.*.product_id' => 'required',
        'orderProducts.*.quantity' => 'required|numeric|min:1'
    ];

    // MOUNT METHOD
    public function mount()
    {
        $this->suppliers = Supplier::orderBy('business_name')->get();
        $this->payment_conditions = PaymentConditions::orderBy('name')->get();
        $this->payment_methods = PaymentMethods::orderBy('name')->get();
        $this->voucher_types = VoucherTypes::orderBy('name')->get();

        $this->allProducts = Product::where('is_buyable', true)->orderBy('name')->get();
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

    // SUPPLIER IVA CONDITION
    public function updatedCreateFormSupplierId()
    {
        $this->supplier_iva_condition = Supplier::find($this->createForm['supplier_id'])->iva_condition->name ?? '';
        $this->supplier_discriminates_iva = Supplier::find($this->createForm['supplier_id'])->iva_condition->discriminate ?? null;
        $this->reset(['has_order_associated', 'supplier_orders']);
        $this->resetOrderProducts();
    }

    // TOGGLE DIV
    public function updatedHasOrderAssociated()
    {
        if ($this->has_order_associated == 0) {
            $this->supplier_orders = [];
            $this->resetOrderProducts();
        } else {
            $this->supplier_orders = PurchaseOrder::where('supplier_id', $this->createForm['supplier_id'])->where('is_active', true)->get();
        }
    }

    // SUPPLIER ORDER
    public function updatedCreateFormSupplierOrderId($value)
    {
        $this->orderProducts = [];
        $this->orderProducts = ProductPurchaseOrder::where('purchase_order_id', $value)->get()->toArray();
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
        // $this->orderProducts[] = ['product_id' => '', 'quantity' => 1, 'price' => 0, 'subtotal' => 0];
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
            $this->orderProducts[$index]['subtotal'] = number_format($product['quantity'] * $product['price'], 2, '.', '');
            $subtotal += $this->orderProducts[$index]['subtotal'];
        }

        // Subtotal format to 2 decimals
        if($this->supplier_discriminates_iva) {
            $this->createForm['subtotal'] = number_format($subtotal, 2, '.', '');
            $this->createForm['iva'] = number_format($subtotal * 0.21, 2, '.', '');
            $this->createForm['total'] = number_format($subtotal * 1.21, 2, '.', '');
        } else {
            $this->createForm['total'] = number_format($subtotal, 2, '.', '');
        }

    }

    // Si el costo de compra es mayor al costo actual, se debe mostrar un mensaje de alerta
    public function presave()
    {
        $this->validate();

        $products = [];

        foreach ($this->orderProducts as $product) {
            $dbProduct = Product::find($product['product_id']);
            if ($dbProduct->cost < $product['price']) {
                $products[] = $dbProduct->name;
            }
        }

        if (count($products) > 0) {
            // $this->emit('error', 'El costo de compra de los siguientes productos es mayor al costo actual: ' . implode(', ', $products));
            $this->emit('moreExpensiveProducts', 'El costo de compra de los siguientes productos es mayor al costo actual: ' . implode(', ', $products));
        } else {
            $this->save(true);
            // dd('Pasa el presave y no hay productos más caros');
        }
    }

    // CREATE PURCHASE
    public function save($updateCosts)
    {
        // Validamos los datos
        $this->validate();

        // Creamos la compra
        $purchase = Purchase::create($this->createForm);

        // Creamos los productos de la compra en la tabla pivote
        if ($updateCosts) {
            foreach ($this->orderProducts as $product) {
                $purchase->products()->attach($product['product_id'], [
                    'quantity' => $product['quantity'],
                    'price' => $product['price'],
                    'subtotal' => $product['quantity'] * $product['price']
                ]);

                // Actualizamos el precio de venta de cada producto si su costo es mayor al de compra
                $dbProduct = Product::find($product['product_id']);
                if ($dbProduct->cost < $product['price']) {

                    $dbProduct->prices()->create([
                        'cost' => $dbProduct->cost,
                        'selling_price' => $dbProduct->selling_price,
                        'user_id' => auth()->user()->id
                    ]);

                    // Actualizamos los precios
                    $dbProduct->cost = $product['price'];
                    $dbProduct->selling_price = $product['price'] * $dbProduct->margin;
                    $dbProduct->save();
                }
            }
        } else {
            foreach ($this->orderProducts as $product) {
                $purchase->products()->attach($product['product_id'], [
                    'quantity' => $product['quantity'],
                    'price' => $product['price'],
                    'subtotal' => $product['quantity'] * $product['price']
                ]);
            }
        }

        // Obtenemos el subtotal de la compra
        $subtotal = $purchase->products->sum(function ($product) {
            return $product->pivot->subtotal;
        });

        // Obtenemos el iva de la compra
        $iva = $subtotal * 0.21;

        // Actualizamos el subtotal, iva y total de la compra
        if ($this->supplier_discriminates_iva) {
            $purchase->subtotal = $subtotal;
            $purchase->iva = $iva;
            $purchase->total = $subtotal + $iva;
        } else {
            $purchase->subtotal = $subtotal;
            $purchase->iva = 0;
            $purchase->total = $subtotal;
        }

        // Actualizamos el stock de los productos
        foreach ($purchase->products as $product) {
            $p = Product::find($product->id);
            $p->update([
                'real_stock' => $p->real_stock + $product->pivot->quantity
            ]);
        }

        // Actualizamos la orden de compra
        if ($this->createForm['supplier_order_id'] != null) {
            $purchaseOrder = PurchaseOrder::find($purchase->supplier_order_id);
            $purchaseOrder->update([
                'its_done' => true
            ]);
        }

        // Añadimos 1 compra al proveedor
        $supplier = Supplier::find($purchase->supplier_id);
        $supplier->update([
            'total_purchases' => $supplier->total_purchases + 1
        ]);


        // Reseteamos los campos
        $this->reset(['createForm', 'orderProducts']);

        // Retornamos a la vista de compras
        $id = $purchase->id;
        $message = '¡La compra se ha creado correctamente! Su ID es: ' . $id;
        session()->flash('flash.banner', $message);
        return redirect()->route('admin.purchases.index');
    }

    public function render()
    {
        return view('livewire.purchases.create-purchase');
    }
}
