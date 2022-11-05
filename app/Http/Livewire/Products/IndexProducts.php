<?php

namespace App\Http\Livewire\Products;

use App\Models\Measure;
use App\Models\Product;
use Livewire\Component;
use App\Models\WoodType;
use App\Models\ProductName;
use Livewire\WithPagination;

class IndexProducts extends Component
{
    use WithPagination;

    public $search;
    public $product_names, $product_name;
    public $measures, $measure;
    public $wood_types, $wood_type;
    public $stock_parameter;
    public $filtersDiv = false;

    public function mount()
    {
        $this->product_names = ProductName::all();
        $this->wood_types = WoodType::all();
        $this->measures = Measure::orderBy('favorite', 'desc')->get();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function toggleFiltersDiv()
    {
        $this->filtersDiv = !$this->filtersDiv;
        $this->reset(['product_name', 'wood_type', 'measure', 'stock_parameter']);
    }

    public function render()
    {
        $products = Product::whereHas('productType.product_name', function ($query) {
            $query->where('name', 'LIKE', '%' . $this->product_name . '%');
        })->whereHas('productType.measure', function ($query) {
            $query->where('name', 'LIKE', '%' . $this->measure . '%');
        })->whereHas('woodType', function ($query) {
            $query->where('name', 'LIKE', '%' . $this->wood_type . '%');
        })->when($this->stock_parameter ?? null, function($query) {
            $query->whereRaw('real_stock ' . $this->stock_parameter . ' minimum_stock');
        })->where('name', 'LIKE', '%' . $this->search . '%')->paginate(10);

        return view('livewire.products.index-products', compact('products'));
    }
}
