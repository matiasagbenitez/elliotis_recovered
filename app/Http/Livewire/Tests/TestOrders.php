<?php

namespace App\Http\Livewire\Tests;

use App\Models\Product;
use App\Models\SaleOrder;
use Livewire\Component;

class TestOrders extends Component
{
    public $products = [];
    public $previousProducts = [];

    public function mount()
    {
        $this->getProductsFromSaleOrders();
        $this->firstProduct();
    }

    public function getProductsFromSaleOrders()
    {
        $order = SaleOrder::find(2);
        $orders = SaleOrder::all();

        // foreach ($orders as $order) {
            foreach ($order->products as $product) {
                $this->products[] = [
                    'product_id' => $product->id,
                    'name' => $product->name,
                    'quantity' => $product->pivot->quantity,
                ];
            }
        // }

        $this->products = collect($this->products)->groupBy('product_id')->map(function ($item) {
            return [
                'product_id' => $item->first()['product_id'],
                'name' => $item->first()['name'],
                'quantity' => $item->sum('quantity'),
            ];
        })->values()->toArray();
    }

    public function firstProduct()
    {
        // $product = Product::find($this->products[0]['product_id']);

        foreach ($this->products as $product) {
            $prod = Product::find($product['product_id']);
            $this->previousProducts[] = [
                'product_id' => $prod->id,
                'name' => $prod->name,
                'quantity' => $prod->real_stock,
                'needed' => $product['quantity'] - $prod->real_stock,
            ];

            $unities = $prod->productType->unity->unities * ($product['quantity'] - $prod->real_stock);
            $this->previousProducts = $this->getPreviousProducts($prod, $this->previousProducts, $unities);
        }

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

    public function render()
    {
        return view('livewire.tests.test-orders');
    }
}
