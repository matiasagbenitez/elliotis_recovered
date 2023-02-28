<?php

namespace App\Http\Livewire\TrunkLots;

use App\Models\TrunkLot;
use App\Models\TrunkSublot;
use Livewire\Component;
use Livewire\WithPagination;

class IndexTrunkLots extends Component
{
    use WithPagination;
    public $products;
    public $resume_view = false;

    public function toggleResumeView()
    {
        $this->resume_view = !$this->resume_view;
    }

    public function render()
    {
        $trunk_lots = TrunkLot::withCount(['trunkSublots' => function ($query) {
            $query->where('available', true);
        }])->orderBy('trunk_sublots_count', 'desc')->paginate(5);

        $this->products = TrunkSublot::groupBy('product_id')
        ->join('products', 'products.id', '=', 'trunk_sublots.product_id')
        ->select('products.name as product_name', 'trunk_sublots.product_id')
        ->selectRaw('product_id, sum(actual_quantity) as actual_quantity')
        ->selectRaw('product_id, count(product_id) as lots_count')
        ->where('available', true)
        ->orderBy('product_id', 'asc')->get();

        return view('livewire.trunk-lots.index-trunk-lots', compact('trunk_lots'));
    }
}
