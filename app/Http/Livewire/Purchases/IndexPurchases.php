<?php

namespace App\Http\Livewire\Purchases;

use Carbon\Carbon;
use App\Models\Product;
use Livewire\Component;
use App\Models\Purchase;
use App\Models\Supplier;
use Termwind\Components\Dd;
use App\Models\VoucherTypes;
use Livewire\WithPagination;
use App\Models\PurchaseOrder;
use App\Models\TrunkPurchase;

class IndexPurchases extends Component
{
    use WithPagination;
    public $suppliers = [], $voucher_types = [];
    public $sort = 'id';
    public $direction = 'desc';
    public $total_purchases;

    public $filters = [
        'supplier' => '',
        'voucherType' => '',
        'fromDate' => '',
        'toDate' => '',
    ];

    protected $listeners = ['refresh' => 'render', 'disable', 'confirm'];

    public function mount()
    {
        $this->suppliers = Supplier::orderBy('business_name')->get();
        $this->voucher_types = VoucherTypes::all();
    }

    public function order($sort)
    {
        if ($this->sort == $sort) {
            if ($this->direction == 'desc') {
                $this->direction = 'asc';
            } else {
                $this->direction = 'desc';
            }
        } else {
            $this->sort = $sort;
            $this->direction = 'desc';
        }
    }

    public function resetFilters()
    {
        $this->filters = [
            'supplier' => '',
            'voucherType' => '',
            'fromDate' => '',
            'toDate' => '',
        ];
    }

    public function disable($id, $reason, $disableOrder)
    {
        $purchase_month = Carbon::parse(Purchase::find($id)->date)->format('m');

        if ($purchase_month == now()->month) {
            try {
                $purchase = Purchase::find($id);
                $purchase->is_active = false;
                $purchase->cancelled_by = auth()->user()->id;
                $purchase->cancelled_at = now();
                $purchase->cancel_reason = $reason;
                $purchase->save();

                // foreach ($purchase->products as $product) {
                //     $p = Product::find($product->id);
                //     $p->update([
                //         'real_stock' => $p->real_stock - $product->pivot->quantity
                //     ]);
                // }

                $supplier = Supplier::find($purchase->supplier_id);
                $supplier->update([
                    'total_purchases' => $supplier->total_purchases - 1
                ]);

                $purchaseOrder = PurchaseOrder::find($purchase->supplier_order_id);

                if ($purchaseOrder) {
                    if ($disableOrder) {
                        $purchaseOrder->update([
                            'is_active' => false
                        ]);
                        $this->emit('success', '¡La compra y la orden se han anulado correctamente!');
                    } else {
                        $purchaseOrder->update([
                            'is_active' => true,
                            'its_done' => null
                        ]);
                        $this->emit('success', '¡La compra se ha anulado correctamente!');
                    }
                } else {
                    $this->emit('success', '¡La compra se ha anulado correctamente!');
                }

                $this->emit('refresh');
            } catch (\Exception $e) {
                $this->emit('error', 'No es posible anular la compra.');
            }
        } else {
            $this->emit('error', 'No es posible anular una compra del mes pasado.');
        }
    }

    public function confirm($id)
    {
        try {
            $purchase = Purchase::find($id);

            $trunkPurchase = TrunkPurchase::create([
                'purchase_id' => $purchase->id,
                'code' => 'C-' . $purchase->id
            ]);

            $trunkPurchase->trunk_lots()->createMany(
                $purchase->products->map(function ($product) {
                    return [
                        'product_id' => $product->id,
                        'code' => 'LR-' . rand(1000, 9999),
                        'initial_quantity' => $product->pivot->quantity,
                        'actual_quantity' => $product->pivot->quantity,
                    ];
                })
            );

            $purchase->update([
                'is_confirmed' => true,
                'confirmed_at' => now(),
                'confirmed_by' => auth()->user()->id
            ]);

            // Actualizamos el stock de los productos
            foreach ($purchase->products as $product) {
                $p = Product::find($product->id);
                $p->update([
                    'real_stock' => $p->real_stock + $product->pivot->quantity
                ]);
            }

            $this->emit('success', '¡La compra se ha confirmado correctamente! Lotes registrados para producción.');
        } catch (\Exception $e) {
            $this->emit('error', 'No es posible confirmar la compra.');
        }
    }

    public function render()
    {
        $purchases = Purchase::filter($this->filters)->orderBy($this->sort, $this->direction)->paginate(8);

        return view('livewire.purchases.index-purchases', compact('purchases'));
    }
}
