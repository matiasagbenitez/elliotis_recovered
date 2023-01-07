<?php

namespace App\Http\Livewire\Productions;

use Livewire\Component;
use App\Models\SaleOrder;
use App\Http\Services\NecessaryProductionService;

class ProductionCalculation extends Component
{
    public $products = [];
    public $previousProducts = [];

    public function mount()
    {
        $orders = SaleOrder::where('is_active', true)->get();

        foreach ($orders as $order) {
            foreach ($order->products as $product) {
                $this->products[] = [
                    'product_id' => $product->id,
                    'name' => $product->name,
                    'quantity' => $product->pivot->quantity,
                ];
            }
        }

        $this->products = collect($this->products)->groupBy('product_id')->map(function ($item) {
            return [
                'product_id' => $item->first()['product_id'],
                'name' => $item->first()['name'],
                'quantity' => $item->sum('quantity'),
            ];
        })->values()->toArray();

        $this->previousProducts = NecessaryProductionService::calculate();
    }

    public function render()
    {
        return view('livewire.productions.production-calculation');
    }
}
