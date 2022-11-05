<?php

namespace App\Http\Livewire\SaleOrders;

use App\Models\Sale;
use App\Models\User;
use Livewire\Component;
use App\Models\SaleOrder;

class ShowSaleOrder extends Component
{
    public $saleOrder, $sale;
    public $user_who_cancelled = '';

    public function mount(SaleOrder $saleOrder)
    {
        if ($saleOrder->cancelled_by) {
            $id = $saleOrder->cancelled_by;
            $this->user_who_cancelled = User::find($id)->name;
        }
        $this->saleOrder = $saleOrder;

        $this->sale = Sale::where('client_order_id', $saleOrder->id)->first();
    }

    public function render()
    {
        return view('livewire.sale-orders.show-sale-order');
    }
}
