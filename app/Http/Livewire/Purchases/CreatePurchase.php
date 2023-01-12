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

    public $type_of_purchase = 1;

    public $tn_final = 0;

    public $weightForm = [
        'totalTn' => 0,
        'priceTn' => 0
    ];

    // PRODUCTS
    public $orderProducts = [];
    public $allProducts = [];

    // CREATE FORM
    public $createForm = [
        'user_id' => '',
        'date' => '',
        'supplier_id' => '',
        'supplier_order_id' => '',
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
        $allSuppliers = Supplier::orderBy('business_name')->get();

        foreach ($allSuppliers as $supplier) {
            $this->suppliers[] = [
                'id' => $supplier->id,
                'name' => $supplier->business_name,
                'iva_condition' => $supplier->iva_condition->name,
                'discriminates_iva' => $supplier->iva_condition->discriminate ? 'Discrimina' : 'No discrimina'
            ];
        }

        $this->payment_conditions = PaymentConditions::orderBy('name')->get();
        $this->payment_methods = PaymentMethods::orderBy('name')->get();
        $this->voucher_types = VoucherTypes::orderBy('name')->get();

        $this->createForm['date'] = date('Y-m-d');

        $this->allProducts = Product::where('is_buyable', true)->orderBy('name')->get();
        $this->orderProducts = [
            ['product_id' => '', 'quantity' => 1, 'tn_total' => 0, 'tn_price' => 0, 'subtotal' => 0]
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
    public function updatedCreateFormSupplierId($value)
    {
        $supplier = Supplier::find($value);
        $this->supplier_iva_condition = $supplier->iva_condition->name;
        $this->supplier_discriminates_iva = $supplier->iva_condition->discriminate ? true : false;

        $this->orderProducts = [
            ['product_id' => '', 'quantity' => 1, 'tn_total' => 0, 'tn_price' => 0, 'subtotal' => 0]
        ];

        $this->createForm = [
            'supplier_id' => $value,
            'date' => date('Y-m-d'),
            'subtotal' => 0,
            'iva' => 0,
            'total' => 0,
        ];

        $this->weightForm = [
            'totalTn' => 0,
            'priceTn' => 0
        ];
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
        if (empty($this->orderProducts)) {
            $this->reset('weightForm');
            $this->createForm = [
                'subtotal' => 0,
                'iva' => 0,
                'total' => 0,
            ];
        }

        if (count($this->orderProducts) == count($this->allProducts)) {
            return;
        }

        if (!empty($this->orderProducts[count($this->orderProducts) - 1]['product_id']) || count($this->orderProducts) == 0) {
            $this->orderProducts[] =  ['product_id' => '', 'quantity' => 1, 'tn_total' => 0, 'tn_price' => 0, 'subtotal' => 0];
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
        $tn_final = 0;

        if ($this->type_of_purchase == 1) {
            foreach ($this->orderProducts as $index => $product) {
                if (empty($product['tn_total'])) {
                    $this->orderProducts[$index]['subtotal'] = 0;
                } else {
                    $aux = $product['tn_total'] * $product['tn_price'];
                    $this->orderProducts[$index]['subtotal'] = number_format($aux, 2, '.', '');
                    $tn_final += $this->orderProducts[$index]['tn_total'];
                    $subtotal += $this->orderProducts[$index]['subtotal'];
                }
            }
        }

        // Resumen de la venta
        if ($this->type_of_purchase == 1) {

            if ($this->supplier_discriminates_iva) {
                $this->createForm['subtotal'] = number_format($subtotal, 2, '.', '');
                $this->createForm['iva'] = number_format($subtotal * 0.21, 2, '.', '');
                $this->createForm['total'] = number_format($subtotal * 1.21, 2, '.', '');
            } else {
                $this->createForm['subtotal'] = number_format($subtotal / 1.21, 2, '.', '');
                $this->createForm['iva'] = number_format($subtotal / 1.21 * 0.21, 2, '.', '');
                $this->createForm['total'] = number_format($subtotal, 2, '.', '');
            }
            $this->tn_final = $tn_final;
        }
    }

    public function updatedWeightForm()
    {
        if (!empty($this->weightForm['totalTn']) && $this->weightForm['priceTn'] > 0) {
            $subtotal = $this->weightForm['totalTn'] * $this->weightForm['priceTn'];
            if ($this->supplier_discriminates_iva) {
                $this->createForm['subtotal'] = number_format($subtotal, 2, '.', '');
                $this->createForm['iva'] = number_format($subtotal * 0.21, 2, '.', '');
                $this->createForm['total'] = number_format($subtotal * 1.21, 2, '.', '');
            } else {
                $this->createForm['subtotal'] = number_format($subtotal / 1.21, 2, '.', '');
                $this->createForm['iva'] = number_format($subtotal / 1.21 * 0.21, 2, '.', '');
                $this->createForm['total'] = number_format($subtotal, 2, '.', '');
            }
        } else {
            $this->createForm['subtotal'] = 0;
            $this->createForm['iva'] = 0;
            $this->createForm['total'] = 0;
        }
    }

    // CREATE PURCHASE
    public function save()
    {
        $this->createForm['user_id'] = auth()->user()->id;

        $this->validate();

        $purchase = Purchase::create($this->createForm);

        if ($this->type_of_purchase == 1) {
            if ($this->supplier_discriminates_iva) {
                foreach ($this->orderProducts as $product) {
                    $purchase->products()->attach($product['product_id'], [
                        'quantity' => $product['quantity'],
                        'tn_total' => $product['tn_total'],
                        'tn_price' => $product['tn_price'],
                        'subtotal' => $product['subtotal'],
                    ]);
                }
            } else {
                foreach ($this->orderProducts as $product) {
                    $purchase->products()->attach($product['product_id'], [
                        'quantity' => $product['quantity'],
                        'tn_total' => $product['tn_total'],
                        'tn_price' => $product['tn_price'] / 1.21,
                        'subtotal' => $product['subtotal'] / 1.21,
                    ]);
                }
            }
        } elseif ($this->type_of_purchase == 2) {
            if ($this->supplier_discriminates_iva) {

                // Calculamos el peso aproximado de cada producto basado en la cantidad de cada uno $product['quantity] y el total de toneladas $weightForm['totalTn']
                $totalQuantity = collect($this->orderProducts)->sum('quantity');
                $totalTn = $this->weightForm['totalTn'];

                foreach ($this->orderProducts as $product) {
                    $tn = ($product['quantity'] * $totalTn) / $totalQuantity;
                    $purchase->products()->attach($product['product_id'], [
                        'quantity' => $product['quantity'],
                        'tn_total' => $tn,
                        'tn_price' => $this->weightForm['priceTn'],
                        'subtotal' => $tn * $this->weightForm['priceTn'],
                    ]);
                }
            } else {

                $totalQuantity = collect($this->orderProducts)->sum('quantity');
                $totalTn = $this->weightForm['totalTn'];

                foreach ($this->orderProducts as $product) {
                    $tn = ($product['quantity'] * $totalTn) / $totalQuantity;
                    $purchase->products()->attach($product['product_id'], [
                        'quantity' => $product['quantity'],
                        'tn_total' => $tn,
                        'tn_price' => $this->weightForm['priceTn'] / 1.21,
                        'subtotal' => ($tn * $this->weightForm['priceTn']) / 1.21,
                    ]);
                }
            }
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
