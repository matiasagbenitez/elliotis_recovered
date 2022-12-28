<?php

namespace App\Http\Livewire\PreviousProducts;

use App\Models\Product;
use Livewire\Component;

class IndexPreviousProducts extends Component
{
    public $allProducts =  '';
    public $selectedProduct =  '';
    public $previous_product = null;
    public $filter =  '';

    public function mount()
    {
        $this->allProducts = Product::all();
    }

    public function updatedFilter()
    {
        $this->selectedProduct = Product::find($this->filter);
        $this->previous_product = $this->selectedProduct->previousProduct;
        // dd($this->previous_product);
        $this->render();
    }

    public function render()
    {
        return view('livewire.previous-products.index-previous-products');
    }
}
