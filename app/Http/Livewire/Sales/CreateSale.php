<?php

namespace App\Http\Livewire\Sales;

use App\Models\Sale;
use App\Models\Client;
use App\Models\Sublot;
use App\Models\Product;
use Livewire\Component;
use App\Models\Supplier;
use App\Models\SaleOrder;
use Termwind\Components\Dd;
use App\Models\VoucherTypes;
use Livewire\WithFileUploads;
use App\Models\PaymentMethods;
use App\Models\ProductSaleOrder;
use App\Models\PaymentConditions;
use Illuminate\Support\Facades\Auth;
use App\Http\Services\NecessaryProductionService;

class CreateSale extends Component
{

    use WithFileUploads;

    // SALE PARAMETERS
    public $clients = [], $payment_conditions = [], $payment_methods = [], $voucher_types = [];
    public $client_discriminates_iva, $has_order_associated = '', $client_orders = [];

    // PRODUCTS
    public $orderSublots = [];
    public $allSublots = [];
    public $allSublotsFormated = [];

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

    protected $validationAttributes = [
        'createForm.client_id' => 'cliente',
        'createForm.date' => 'fecha',
        'createForm.client_order_id' => 'orden asociada',
        'createForm.payment_condition_id' => 'condición de pago',
        'createForm.payment_method_id' => 'método de pago',
        'createForm.voucher_type_id' => 'tipo de comprobante',
        'createForm.voucher_number' => 'número de comprobante',
        'createForm.subtotal' => 'subtotal',
        'createForm.iva' => 'IVA',
        'createForm.total' => 'total',
        'createForm.observations' => 'observaciones',
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
        'orderSublots.*.product_id' => 'required',
        'orderSublots.*.m2_unitary' => 'required|numeric|min:1',
        'orderSublots.*.quantity' => 'required|numeric|min:1',
        'orderSublots.*.m2_total' => 'required|numeric|min:1',
        'orderSublots.*.m2_price' => 'required|numeric|min:1',
    ];

    protected $listeners = ['bestTry'];

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

        $this->allSublots = Sublot::where('available', true)->whereHas('product', function ($query) {
            $query->where('is_salable', true);
        })->get();

        foreach ($this->allSublots as $sublot) {
            $this->allSublotsFormated[] = [
                'id' => $sublot->id,
                'product_id' => $sublot->product_id,
                'text' => $sublot->product->name . ' - Sublote: ' . $sublot->code . ' - Lote: ' . $sublot->lot->code . ' [' . $sublot->actual_quantity . ' unidades disponibles]'
            ];
        }

        $this->orderSublots = [
            ['sublot_id' => '', 'product_id' => '', 'm2_unitary' => 0, 'quantity' => 1, 'm2_total' => 0, 'm2_price' => 0, 'subtotal' => 0]
        ];
    }

    // RESET ORDER PRODUCTS
    public function resetOrderSublots()
    {
        $this->orderSublots = [
            ['sublot_id' => '', 'product_id' => '', 'm2_unitary' => 0, 'quantity' => 1, 'm2_total' => 0, 'm2_price' => 0, 'subtotal' => 0]
        ];
        $this->createForm['subtotal'] = 0;
        $this->createForm['iva'] = 0;
        $this->createForm['total'] = 0;
    }

    public function updatedCreateFormClientId($value)
    {
        $client = Client::find($value);
        $this->client_discriminates_iva = $client->iva_condition->discriminate ? true : false;
        $this->orderSublots = [
            ['sublot_id' => '', 'product_id' => '', 'm2_unitary' => 0, 'quantity' => 1, 'm2_total' => 0, 'm2_price' => 0, 'subtotal' => 0]
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
            $this->resetOrderSublots();
        } else {
            $this->reset('client_orders');
            $this->client_orders = SaleOrder::where('client_id', $this->createForm['client_id'])->where('is_active', true)->get();
        }
    }

    // CLIENT ORDERS
    public function updatedCreateFormClientOrderId($value)
    {
        $this->orderSublots = [];

        $orderDetail = ProductSaleOrder::where('sale_order_id', $value)->get()->toArray();

        foreach ($orderDetail as $product) {

            $sublots = Sublot::where('product_id', $product['product_id'])->where('available', true)->where('area_id', 8)->whereHas('product', function ($query) {
                $query->where('is_salable', true);
            })->get();

            $quantity = $product['quantity'];

            foreach ($sublots as $sublot) {
                if ($sublot->actual_quantity >= $quantity) {
                    $this->orderSublots[] = [
                        'sublot_id' => $sublot->id,
                        'product_id' => $sublot->product_id,
                        'm2_unitary' => $sublot->product->m2,
                        'quantity' => $quantity,
                        'm2_total' => $sublot->product->m2 * $quantity,
                        'm2_price' => $this->client_discriminates_iva ? $sublot->product->m2_price : $sublot->product->m2_price * 1.21,
                        'subtotal' => 0
                    ];
                    $quantity = 0;
                    break;
                } else {
                    $this->orderSublots[] = [
                        'sublot_id' => $sublot->id,
                        'product_id' => $sublot->product_id,
                        'm2_unitary' => $sublot->product->m2,
                        'quantity' => $sublot->actual_quantity,
                        'm2_total' => $sublot->product->m2 * $sublot->actual_quantity,
                        'm2_price' => $this->client_discriminates_iva ? $sublot->product->m2_price : $sublot->product->m2_price * 1.21,
                        'subtotal' => 0
                    ];
                    $quantity -= $sublot->actual_quantity;
                }
            }

            if ($quantity > 0) {
                $this->resetOrderSublots();
                $this->emit('quantitiesError', 'No es posible completar la orden de venta porque no hay suficientes sublotes con stock disponible. ¿Desea completar lo máximo posible con el stock disponible o cancelar la venta?');
            }
        }

        $this->updatedOrderSublots();
    }

    public function bestTry()
    {
        $this->orderSublots = [];

        $orderDetail = ProductSaleOrder::where('sale_order_id', $this->createForm['client_order_id'])->get()->toArray();

        foreach ($orderDetail as $product) {
            $sublots = Sublot::where('product_id', $product['product_id'])->where('available', true)->where('area_id', 8)->whereHas('product', function ($query) {
                $query->where('is_salable', true);
            })->get();

            $quantity = $product['quantity'];

            foreach ($sublots as $sublot) {
                if ($quantity > 0) {
                    if ($sublot->actual_quantity >= $quantity) {
                        $this->orderSublots[] = [
                            'sublot_id' => $sublot->id,
                            'product_id' => $sublot->product_id,
                            'm2_unitary' => $sublot->product->m2,
                            'quantity' => $quantity,
                            'm2_total' => $sublot->product->m2 * $quantity,
                            'm2_price' => $this->client_discriminates_iva ? $sublot->product->m2_price : $sublot->product->m2_price * 1.21,
                            'subtotal' => 0
                        ];
                        $quantity = 0;
                    } else {
                        $this->orderSublots[] = [
                            'sublot_id' => $sublot->id,
                            'product_id' => $sublot->product_id,
                            'm2_unitary' => $sublot->product->m2,
                            'quantity' => $sublot->actual_quantity,
                            'm2_total' => $sublot->product->m2 * $sublot->actual_quantity,
                            'm2_price' => $this->client_discriminates_iva ? $sublot->product->m2_price : $sublot->product->m2_price * 1.21,
                            'subtotal' => 0
                        ];
                        $quantity -= $sublot->actual_quantity;
                    }
                }
            }

            $this->updatedOrderSublots();
        }
    }

    // ADD PRODUCT
    public function addProduct()
    {
        if (count($this->orderSublots) == count($this->allSublots)) {
            return;
        }

        if (!empty($this->orderSublots[count($this->orderSublots) - 1]['sublot_id']) || count($this->orderSublots) == 0) {
            $this->orderSublots[] = ['sublot_id' => '', 'product_id' => '', 'm2_unitary' => 0, 'quantity' => 1, 'm2_total' => 0, 'm2_price' => 0, 'subtotal' => 0];
        }
    }

    // IS PRODUCT IN ORDER
    public function isSublotInOrder($sublot_id)
    {
        foreach ($this->orderSublots as $sublot) {
            if ($sublot['sublot_id'] == $sublot_id) {
                return true;
            }
        }
        return false;
    }

    // REMOVE PRODUCT
    public function removeProduct($index)
    {
        unset($this->orderSublots[$index]);
        $this->orderSublots = array_values($this->orderSublots);
        $this->updatedOrderSublots();
    }

    // UPDATED ORDER PRODUCTS
    public function updatedOrderSublots()
    {
        $subtotal = 0;

        // Complete orderSublots['product_id']
        foreach ($this->orderSublots as $index => $sublot) {
            if (!empty($sublot['sublot_id'])) {
                $this->orderSublots[$index]['product_id'] = Sublot::find($sublot['sublot_id'])->product_id;
            }
        }

        foreach ($this->orderSublots as $index => $product) {
            if (empty($product['quantity']) || empty($product['m2_price'])) {
                $this->orderSublots[$index]['subtotal'] = 0;
                continue;
            } else {
                $this->orderSublots[$index]['m2_unitary'] = Product::find($product['product_id'])->m2 ?? 0;
                $this->orderSublots[$index]['m2_total'] = number_format($this->orderSublots[$index]['quantity'] * $this->orderSublots[$index]['m2_unitary'], 2, '.', '');
                $this->orderSublots[$index]['subtotal'] = number_format($this->orderSublots[$index]['m2_total'] * $this->orderSublots[$index]['m2_price'], 2, '.', '');
                $subtotal += $this->orderSublots[$index]['subtotal'];
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
        $price = Product::find($this->orderSublots[$index]['product_id'])->m2_price ?? 0;

        if (!$this->client_discriminates_iva) {
            $price = $price * 1.21;
        }

        $this->orderSublots[$index]['m2_price'] = number_format($price, 2, '.', '');
        $this->updatedOrderSublots();
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
            return;
        }


        // Controlamos que exista stock para todos los productos de la orden, caso contrario, mostramos un mensaje de error
        foreach ($this->orderSublots as $orderSublot) {
            $sublot = Sublot::find($orderSublot['sublot_id']);
            if ($sublot->actual_quantity < $orderSublot['quantity']) {
                $this->emit('stockError', 'No hay stock suficiente para el producto ' . $sublot->product->name . ' en el sublote ' . $sublot->code . '.' );
                return;
            }
        }

        // dd($orderSublot);
        // Validamos los campos
        $this->validate();

        $this->createForm['user_id'] = Auth::user()->id;

        try {
            // Creamos la venta con los datos del formulario
            $sale = Sale::create($this->createForm);

            // Creamos los productos de la venta en la tabla pivote
            foreach ($this->orderSublots as $sublot) {
                $sale->products()->attach($sublot['product_id'], [
                    'sublot_id' => $sublot['sublot_id'],
                    'm2_unitary' => $sublot['m2_unitary'],
                    'quantity' => $sublot['quantity'],
                    'm2_total' => $sublot['m2_total'],
                    'm2_price' => $this->client_discriminates_iva ? $sublot['m2_price'] : $sublot['m2_price'] / 1.21,
                    'subtotal' => $this->client_discriminates_iva ? $sublot['subtotal'] : $sublot['subtotal'] / 1.21,
                ]);

                // Actualizamos el stock del sublote
                $sublot_aux = Sublot::find($sublot['sublot_id']);
                $sublot_aux->update([
                    'actual_quantity' => $sublot_aux->actual_quantity - $sublot['quantity'],
                    'available' => $sublot_aux->actual_quantity - $sublot['quantity'] > 0 ? true : false,
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
            // dd($th->getMessage());
            $this->emit('error', '¡Ha ocurrido un error! Verifica la información ingresada.');
        }
    }

    public function render()
    {
        return view('livewire.sales.create-sale');
    }
}
