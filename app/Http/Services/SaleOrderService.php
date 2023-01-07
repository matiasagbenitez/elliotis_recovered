<?php

namespace App\Http\Services;

use App\Models\Sale;
use App\Models\Product;
use App\Models\SaleOrder;
use Illuminate\Queue\Console\RetryCommand;

class SaleOrderService
{
    // Array
    public $products = [];
    public $previousProducts = [];

    public static function test(SaleOrder $saleOrder)
    {
        foreach ($saleOrder->products as $product) {
            $products[] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'quantity' => $product->pivot->quantity,
            ];
        }

        $products = collect($products)->groupBy('product_id')->map(function ($item) {
            return [
                'product_id' => $item->first()['product_id'],
                'name' => $item->first()['name'],
                'quantity' => $item->sum('quantity'),
            ];
        })->values()->toArray();

        foreach ($products as $product) {
            $prod = Product::find($product['product_id']);
            $previousProducts[] = [
                'product_id' => $prod->id,
                'name' => $prod->name,
                'quantity' => $prod->real_stock,
                'needed' => ($product['quantity'] - $prod->real_stock < 0 ? 0 : $product['quantity'] - $prod->real_stock),
            ];

            $unities = $prod->productType->unity->unities * ($product['quantity'] - $prod->real_stock);
            $previousProducts = SaleOrderService::getPreviousProducts($prod, $previousProducts, $unities);
        }

        return $previousProducts;
    }

    public static function getPreviousProducts(Product $product, &$previousProducts = [], $unities)
    {
        $previousProduct = $product->previousProduct;
        if ($previousProduct) {
            $newUnities = $unities - $previousProduct->real_stock;
            $previousProducts[] = [
                'id' => $previousProduct->id,
                'name' => $previousProduct->name,
                'quantity' => $previousProduct->real_stock,
                'needed' => $newUnities < 0 ? 0 : $newUnities,
            ];

            SaleOrderService::getPreviousProducts($previousProduct, $previousProducts, $newUnities);
        }

        return $previousProducts;
    }
}
