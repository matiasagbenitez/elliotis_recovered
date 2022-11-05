<?php

namespace App\Http\Livewire\Purchases;

use App\Models\Product;
use Livewire\Component;
use App\Models\Purchase;
use App\Models\PurchaseOrder;
use App\Models\Supplier;
use App\Models\VoucherTypes;
use Carbon\Carbon;
use Livewire\WithPagination;
use Termwind\Components\Dd;

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

    protected $listeners = ['refresh' => 'render', 'disable'];

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

    // public function updatedFilters()
    // {
    //     $this->total_purchases = Purchase::filter($this->filters)->where('is_active', true)->sum('total');
    // }

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

                foreach ($purchase->products as $product) {
                    $p = Product::find($product->id);
                    $p->update([
                        'real_stock' => $p->real_stock - $product->pivot->quantity
                    ]);
                }

                $supplier = Supplier::find($purchase->supplier_id);
                $supplier->update([
                    'total_purchases' => $supplier->total_purchases - 1
                ]);

                $purchaseOrder = PurchaseOrder::find($purchase->supplier_order_id);
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

                $this->emit('refresh');
            } catch (\Exception $e) {
                $this->emit('error', 'No es posible anular la compra.');
            }
        } else {
            $this->emit('error', 'No es posible anular una compra del mes pasado.');
        }
    }

    public function render()
    {
        $purchases = Purchase::filter($this->filters)->orderBy($this->sort, $this->direction)->paginate(8);

        return view('livewire.purchases.index-purchases', compact('purchases'));
    }
}
