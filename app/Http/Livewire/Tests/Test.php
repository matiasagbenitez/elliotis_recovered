<?php

namespace App\Http\Livewire\Tests;

use App\Models\Product;
use Livewire\Component;

class Test extends Component
{
    public $purchaseProduct, $purchaseQuantity;
    public $previousProducts = [];

    public function mount()
    {
        $this->purchaseProduct = Product::find(20);
        $this->purchaseQuantity = 1;

        $this->previousProducts = $this->getPreviousProducts($this->purchaseProduct);
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
        return view('livewire.tests.test')->layout('layouts.guest');
    }
}
