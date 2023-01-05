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
        $this->purchaseProduct = Product::find(19);
        $this->purchaseQuantity = 10;

        $needed = $this->purchaseQuantity - $this->purchaseProduct->real_stock;
        $this->previousProducts[] = [
            'id' => $this->purchaseProduct->id,
            'name' => $this->purchaseProduct->name,
            'quantity' => $this->purchaseProduct->real_stock,
            'needed' => $needed
        ];

        $unities = $this->purchaseProduct->productType->unity->unities * $needed;

        $this->previousProducts = $this->getPreviousProducts($this->purchaseProduct, $this->previousProducts, $unities);

        $this->calculate();
    }

    public function getPreviousProducts(Product $product, &$previousProducts = [], $unities)
    {
        $previousProduct = $product->previousProduct;
        if ($previousProduct) {
            $newUnities = $unities - $previousProduct->real_stock;
            $previousProducts[] = [
                'id' => $previousProduct->id,
                'name' => $previousProduct->name,
                'quantity' => $previousProduct->real_stock,
                'needed' => $newUnities
            ];
            $this->getPreviousProducts($previousProduct, $previousProducts, $newUnities);
        }

        return $previousProducts;
    }

    public function calculate()
    {
        if ($this->purchaseProduct->phase_id == 5) {

        }
    }

    public function render()
    {
        return view('livewire.tests.test')->layout('layouts.guest');
    }
}
