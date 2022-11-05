<?php

namespace App\Http\Livewire\Sales;

use App\Models\Sale;
use App\Models\Client;
use App\Models\Product;
use App\Models\SaleOrder;
use Livewire\Component;
use App\Models\VoucherTypes;
use Carbon\Carbon;
use Livewire\WithPagination;

class IndexSales extends Component
{

    use WithPagination;
    public $clients = [], $voucher_types = [];
    public $sort = 'id';
    public $direction = 'desc';
    public $total_sales;

    public $filters = [
        'client' => '',
        'voucherType' => '',
        'fromDate' => '',
        'toDate' => '',
    ];

    protected $listeners = ['refresh' => 'render', 'disable'];

    public function mount()
    {
        $this->clients = Client::orderBy('business_name')->get();
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
    //     $this->total_sales = Sale::filter($this->filters)->where('is_active', true)->sum('total');
    // }

    public function resetFilters()
    {
        $this->filters = [
            'client' => '',
            'voucherType' => '',
            'fromDate' => '',
            'toDate' => '',
        ];
    }

    public function disable($id, $reason, $disableOrder)
    {
        $sale_month = Carbon::parse(Sale::find($id)->date)->format('m');

        if ($sale_month == now()->month) {
            try {
                $sale = Sale::find($id);
                $sale->is_active = false;
                $sale->cancelled_by = auth()->user()->id;
                $sale->cancelled_at = now();
                $sale->cancel_reason = $reason;
                $sale->save();

                foreach ($sale->products as $product) {
                    $p = Product::find($product->id);
                    $p->update([
                        'real_stock' => $p->real_stock + $product->pivot->quantity
                    ]);
                }

                $client = Client::find($sale->client_id);
                $client = $client->update([
                    'total_sales' => $client->total_sales - 1
                ]);

                $saleOrder = SaleOrder::find($sale->client_order_id);
                if ($disableOrder) {
                    $saleOrder->update([
                        'is_active' => false
                    ]);
                    $this->emit('success', '¡La venta y la orden se han anulado correctamente!');
                } else {
                    $saleOrder->update([
                        'is_active' => true,
                        'its_done' => null
                    ]);
                    $this->emit('success', '¡La venta se ha anulado correctamente!');
                }

                $this->emit('refresh');
            } catch (\Exception $e) {
                $this->emit('error', 'No es posible anular la venta.');
            }
        } else {
            $this->emit('error', 'No es posible anular una venta del mes pasado.');
        }
    }

    public function render()
    {
        $sales = Sale::filter($this->filters)->orderBy($this->sort, $this->direction)->paginate(10);

        return view('livewire.sales.index-sales', compact('sales'));
    }
}
