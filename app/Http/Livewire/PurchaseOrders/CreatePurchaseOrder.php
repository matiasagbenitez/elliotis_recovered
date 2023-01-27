<?php

namespace App\Http\Livewire\PurchaseOrders;

use App\Models\Product;
use Livewire\Component;
use App\Models\Supplier;
use App\Models\PurchaseOrder;
use Illuminate\Support\Facades\Auth;

class CreatePurchaseOrder extends Component
{
    public $suppliers = [], $supplier_iva_condition = '', $supplier_discriminates_iva;

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
        'supplier_id' => '',
        'registration_date' => '',
        'subtotal' => 0,
        'iva' => 0,
        'total' => 0,
        'total_weight' => 0,
        'observations' => '',
        'type_of_purchase' => 1,
    ];

    // VALIDATION
    protected $rules = [
        'createForm.supplier_id' => 'required|integer|exists:suppliers,id',
        'createForm.registration_date' => 'required|date',
        'createForm.subtotal' => 'required',
        'createForm.iva' => 'required',
        'createForm.total' => 'required',
        'createForm.observations' => 'nullable|string',
        'createForm.type_of_purchase' => 'required|integer|in:1,2',
        'orderProducts.*.product_id' => 'required',
        'orderProducts.*.quantity' => 'required|numeric|min:1',
        'orderProducts.*.tn_total' => 'required|numeric|min:0',
        'orderProducts.*.tn_price' => 'required|numeric|min:0',
    ];

    public function mount()
    {
        $allSuppliers = Supplier::orderBy('business_name')->get();
        $this->createForm['registration_date'] = date('Y-m-d');

        foreach ($allSuppliers as $supplier) {
            $this->suppliers[] = [
                'id' => $supplier->id,
                'name' => $supplier->business_name,
                'iva_condition' => $supplier->iva_condition->name,
                'discriminates_iva' => $supplier->iva_condition->discriminate ? 'Discrimina' : 'No discrimina'
            ];
        }

        $this->allProducts = Product::where('is_buyable', true)->orderBy('name')->get();
        $this->orderProducts = [
            ['product_id' => '', 'quantity' => 1, 'tn_total' => 0, 'tn_price' => 0, 'subtotal' => 0]
        ];
    }

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
            'registration_date' => date('Y-m-d'),
            'subtotal' => 0,
            'iva' => 0,
            'total' => 0,
            'total_weight' => 0,
            'type_of_purchase' => $this->createForm['type_of_purchase'],
        ];

        $this->weightForm = [
            'totalTn' => 0,
            'priceTn' => 0
        ];
    }

    public function updatedTypeOfPurchase()
    {
        $this->orderProducts = [
            ['product_id' => '', 'quantity' => 1, 'tn_total' => 0, 'tn_price' => 0, 'subtotal' => 0]
        ];

        $this->createForm = [
            'supplier_id' => $this->createForm['supplier_id'],
            'registration_date' => date('Y-m-d'),
            'subtotal' => 0,
            'iva' => 0,
            'total' => 0,
            'total_weight' => 0,
            'type_of_purchase' => $this->createForm['type_of_purchase'],
        ];

        $this->weightForm = [
            'totalTn' => 0,
            'priceTn' => 0
        ];
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
            $this->orderProducts[] = ['product_id' => '', 'quantity' => 1, 'tn_total' => 0, 'tn_price' => 0, 'subtotal' => 0];
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
        $tn_final = 0;

        if ($this->createForm['type_of_purchase'] == 1) {
            foreach ($this->orderProducts as $index => $product) {
                if (empty($product['tn_total']) || empty($product['tn_price'])) {
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
        if ($this->createForm['type_of_purchase'] == 1) {

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

    // CREATE PURCHASE ORDER
    public function save()
    {
        $this->createForm['user_id'] = auth()->user()->id;

        if ($this->createForm['type_of_purchase'] == 1) {
            $this->createForm['total_weight'] = $this->tn_final;
        } elseif ($this->createForm['type_of_purchase'] == 2) {
            $this->createForm['total_weight'] = $this->weightForm['totalTn'];
        }

        $this->validate();

        $purchaseOrder = PurchaseOrder::create($this->createForm);

        try {

            if ($this->createForm['type_of_purchase'] == 1) {

                if ($this->supplier_discriminates_iva) {
                    foreach ($this->orderProducts as $product) {
                        $purchaseOrder->products()->attach($product['product_id'], [
                            'quantity' => $product['quantity'],
                            'tn_total' => $product['tn_total'],
                            'tn_price' => $product['tn_price'],
                            'subtotal' => $product['subtotal'],
                        ]);
                    }
                } else {
                    foreach ($this->orderProducts as $product) {
                        $purchaseOrder->products()->attach($product['product_id'], [
                            'quantity' => $product['quantity'],
                            'tn_total' => $product['tn_total'],
                            'tn_price' => $product['tn_price'] / 1.21,
                            'subtotal' => $product['subtotal'] / 1.21,
                        ]);
                    }
                }

            } elseif ($this->createForm['type_of_purchase'] == 2) {

                $this->createForm['total_weight'] = $this->weightForm['totalTn'];

                if ($this->supplier_discriminates_iva) {

                    $totalQuantity = collect($this->orderProducts)->sum('quantity');
                    $totalTn = $this->weightForm['totalTn'];

                    foreach ($this->orderProducts as $product) {
                        $tn = ($product['quantity'] * $totalTn) / $totalQuantity;
                        $purchaseOrder->products()->attach($product['product_id'], [
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
                        $purchaseOrder->products()->attach($product['product_id'], [
                            'quantity' => $product['quantity'],
                            'tn_total' => $tn,
                            'tn_price' => $this->weightForm['priceTn'] / 1.21,
                            'subtotal' => ($tn * $this->weightForm['priceTn']) / 1.21,
                        ]);
                    }
                }
            }

            // Retornamos a la vista de compras
            $id = $purchaseOrder->id;
            $message = '¡La orden de compra se ha creado correctamente! Su ID es: ' . $id;
            session()->flash('flash.banner', $message);

            return redirect()->route('admin.purchase-orders.show-detail', $purchaseOrder);

        } catch (\Throwable $th) {
            $this->emit('error', '¡Ha ocurrido un error! Por favor, inténtelo de nuevo.');
        }
    }

    public function render()
    {
        return view('livewire.purchase-orders.create-purchase-order');
    }
}
