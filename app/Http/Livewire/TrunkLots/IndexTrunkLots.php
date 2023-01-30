<?php

namespace App\Http\Livewire\TrunkLots;

use App\Models\TrunkLot;
use App\Models\TrunkSublot;
use Livewire\Component;

class IndexTrunkLots extends Component
{
    public $trunk_lots;
    public $products;
    public $resume_view = false;

    public function toggleResumeView()
    {
        $this->resume_view = !$this->resume_view;
    }

    public function render()
    {
        // $this->trunk_lots  = TrunkLot::all();

        // Order Trunk Lots by those with sublots with available = true
        // $this->trunk_lots = TrunkLot::whereHas('trunkSublots', function ($query) {
        //     $query->where('available', true);
        // })->get();

        $this->trunk_lots = TrunkLot::withCount(['trunkSublots' => function ($query) {
            $query->where('available', true);
        }])->orderBy('trunk_sublots_count', 'desc')->get();



        $this->products = TrunkSublot::groupBy('product_id')
        ->join('products', 'products.id', '=', 'trunk_sublots.product_id')
        ->select('products.name as product_name', 'trunk_sublots.product_id')
        ->selectRaw('product_id, sum(actual_quantity) as actual_quantity')
        ->selectRaw('product_id, count(product_id) as lots_count')
        ->where('available', true)
        ->orderBy('product_id', 'asc')->get();

        return view('livewire.trunk-lots.index-trunk-lots');
    }
}
