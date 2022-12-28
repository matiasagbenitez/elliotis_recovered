<?php

namespace App\Http\Livewire\FollowingProducts;

use App\Models\Product;
use Livewire\Component;

class IndexFollowingProducts extends Component
{
    public $allProducts =  '';
    public $selectedProduct =  '';
    public $following_products = null;
    public $filter =  '';

    public function mount()
    {
        // allProducts contains products with following_products.final_product = false
        $this->allProducts = Product::whereHas('followingProducts', function ($query) {
            $query->where('final_product', false);
        })->get();

    }

    public function updatedFilter()
    {
        $this->selectedProduct = Product::find($this->filter);
        $this->following_products = $this->selectedProduct->followingProducts;
        $this->render();
    }

    public function render()
    {
        // $this->following_products = $this->selectedProduct->following_products;
        return view('livewire.following-products.index-following-products');
    }
}
