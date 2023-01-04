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
    public $previousProducts = [];

    public function mount()
    {
        $this->allProducts = Product::all();
    }

    public function updatedFilter($value)
    {
        $product = Product::find($value);
        $this->selectedProduct = $product;
        $this->previous_product = $this->selectedProduct->previousProduct;

        // $this->previousProducts = $this->getPreviousProducts($product);

        $this->render();
    }

    public function getPreviousProducts(Product $product, &$previousProducts = [])
    {
        $previousProduct = $product->previousProduct;
        if ($previousProduct && $previousProduct->id != $product->id) {
            $previousProducts[] = $previousProduct;
            $this->getPreviousProducts($previousProduct, $previousProducts);
        }

        return $previousProducts;
    }

    public function render()
    {
        return view('livewire.previous-products.index-previous-products');
    }
}
