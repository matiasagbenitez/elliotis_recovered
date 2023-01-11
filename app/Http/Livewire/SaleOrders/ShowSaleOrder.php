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
    public $client_discriminates_iva;
    public $stats = [];

    public function mount(SaleOrder $saleOrder)
    {
        if ($saleOrder->cancelled_by) {
            $id = $saleOrder->cancelled_by;
            $this->user_who_cancelled = User::find($id)->name;
        }
        $this->saleOrder = $saleOrder;
        $this->sale = Sale::where('client_order_id', $saleOrder->id)->first();

        $this->client_discriminates_iva = $saleOrder->client->iva_condition->discriminate ? true : false;

        $this->getStats();
    }

    public function getStats()
    {
        foreach ($this->saleOrder->products as $product) {
            $this->stats[] = [
                'name' => $product->name,
                'm2_unitary' => $product->pivot->m2_unitary,
                'quantity' => $product->pivot->quantity,
                'm2_total' => $product->pivot->m2_total,
                'm2_price' => $this->client_discriminates_iva ? $product->pivot->m2_price : $product->pivot->m2_price * 1.21,
                'subtotal' => $this->client_discriminates_iva ? $product->pivot->subtotal : $product->pivot->subtotal * 1.21,
            ];
        }
    }

    public function render()
    {
        return view('livewire.sale-orders.show-sale-order');
    }
}
