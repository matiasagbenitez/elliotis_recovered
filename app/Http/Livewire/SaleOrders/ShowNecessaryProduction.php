<?php

namespace App\Http\Livewire\SaleOrders;

use App\Http\Services\SaleOrderService;
use App\Models\SaleOrder;
use Livewire\Component;

class ShowNecessaryProduction extends Component
{
    public $saleOrder;
    public $products = [];
    public $previousProducts = [];

    public function mount(SaleOrder $saleOrder)
    {
        if (!$saleOrder->is_active) {
            abort(404);
        }

        $this->saleOrder = $saleOrder;

        foreach ($saleOrder->products as $product) {
            $this->products[] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'quantity' => $product->pivot->quantity,
            ];
        }

        $this->products = collect($this->products)->groupBy('product_id')->map(function ($item) {
            return [
                'product_id' => $item->first()['product_id'],
                'name' => $item->first()['name'],
                'quantity' => $item->sum('quantity'),
            ];
        })->values()->toArray();

        $this->previousProducts = SaleOrderService::test($saleOrder);
    }

    public function render()
    {
        return view('livewire.sale-orders.show-necessary-production');
    }
}
