<?php

namespace App\Http\Livewire\Products;

use App\Models\Product;
use App\Models\ProductSale;
use Livewire\Component;

class AddProductsComponent extends Component
{
    public $productSales = [];
    public $productSale = [];

    public $orderProducts = [];
    public $allProducts = [];

    public function mount()
    {
        // $this->productSales = ProductSale group by sale_id (not repeat sale_id)

        $this->allProducts = Product::where('is_salable', true)->get();
        $this->orderProducts = [
            ['product_id' => '', 'quantity' => 1, 'price' => 0, 'subtotal' => 0],
        ];
    }

    public function updatedProductSale($value)
    {
        $this->orderProducts = [];

        // Get product_id and quantity from product_sale table where sale_id = $value
        $this->orderProducts = ProductSale::where('sale_id', $value)->get()->toArray();
        // dd($this->orderProducts);
    }

    public function addProduct()
    {
        // If all products are selected, don't add more
        if (count($this->orderProducts) == count($this->allProducts)) {
            return;
        }

        if (!empty($this->orderProducts[count($this->orderProducts) - 1]['product_id'])) {
            $this->orderProducts[] = ['product_id' => '', 'quantity' => 1, 'price' => 0, 'subtotal' => 0];
        }
    }

    public function isProductInOrder($productId)
    {
        foreach ($this->orderProducts as $orderProduct) {
            if ($orderProduct['product_id'] == $productId) {
                return true;
            }
        }

        return false;
    }

    public function removeProduct($index)
    {
        unset($this->orderProducts[$index]);
        $this->orderProducts = array_values($this->orderProducts);
    }

    public function showProducts()
    {
        dd($this->orderProducts);
    }

    public function render()
    {
        $this->productSales = ProductSale::select('sale_id')->groupBy('sale_id')->get();
        return view('livewire.products.add-products-component');
    }
}
