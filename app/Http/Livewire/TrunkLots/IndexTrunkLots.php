<?php

namespace App\Http\Livewire\TrunkLots;

use App\Models\TrunkLot;
use Livewire\Component;
use App\Models\TrunkPurchase;

class IndexTrunkLots extends Component
{
    public $trunk_purchases;
    public $products;
    public $resume_view = false;

    public function toggleResumeView()
    {
        $this->resume_view = !$this->resume_view;
    }

    public function render()
    {
        $this->trunk_purchases  = TrunkPurchase::all();
        $this->products = TrunkLot::groupBy('product_id')
        ->join('products', 'products.id', '=', 'trunk_lots.product_id')
        ->select('products.name as product_name', 'trunk_lots.product_id')
        ->selectRaw('product_id, sum(actual_quantity) as actual_quantity')
        ->selectRaw('product_id, count(product_id) as lots_count')
        ->orderBy('product_id', 'asc')->get();

        return view('livewire.trunk-lots.index-trunk-lots');
    }
}
