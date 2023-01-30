<?php

namespace App\Http\Livewire\Lots;

use App\Models\Product;
use App\Models\Sublot;
use Livewire\Component;

class SublotsProducts extends Component
{
    public $sublots, $products, $sublotStats;
    public $filter = '';

    public function mount()
    {
        $this->sublots = Sublot::where('available', true)->orderBy('created_at', 'desc')->get();
        $this->products = Product::orderBy('name', 'ASC')->get();
        $this->getSublotStats();
    }

    public function getSublotStats()
    {
        $this->sublotStats = [];
        foreach ($this->sublots as $sublot) {
            $this->sublotStats[] = [
                'id' => $sublot->id,
                'code' => $sublot->code,
                'product' => $sublot->product->name,
                'area' => $sublot->area->name,
                'actual_quantity' => $sublot->actual_quantity,
                'm2' => $sublot->actual_m2 > 0 ? $sublot->actual_m2 . ' m2' : 'N/A',
            ];
        }
    }

    public function updatedFilter()
    {
        if ($this->filter != '') {
            $this->sublots = Sublot::where('available', true)
            ->where('product_id', $this->filter)->orderBy('created_at', 'desc')
            ->get();
        } else {
            $this->sublots = Sublot::where('available', true)->orderBy('created_at', 'desc')->get();
        }
        $this->getSublotStats();
    }

    public function render()
    {
        return view('livewire.lots.sublots-products');
    }
}
