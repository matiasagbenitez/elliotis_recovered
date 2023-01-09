<?php

namespace App\Http\Services;

use App\Models\Product;
use App\Models\SaleOrder;

class NecessaryProductionService
{
    // Array
    public $resume = [];
    public $products = [];
    public $previousProducts = [];

    public static function calculate(SaleOrder $saleOrder = null, $updateStock = false)
    {
        if ($saleOrder) {
            $orders = SaleOrder::where('is_active', true)->where('id', $saleOrder->id)->get();
        } else {
            $orders = SaleOrder::where('is_active', true)->get();
        }

        foreach ($orders as $order) {
            foreach ($order->products as $product) {
                $products[] = [
                    'product_id' => $product->id,
                    'name' => $product->name,
                    'quantity' => $product->pivot->quantity,
                ];
            }
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
                'needed' => $product['quantity'] - $prod->real_stock,
            ];

            if ($updateStock) {
                $prod->update([
                    'necessary_stock' => $product['quantity'] - $prod->real_stock
                ]);
            }

            $unities = $prod->productType->unity->unities * ($product['quantity'] - $prod->real_stock);
            $previousProducts = NecessaryProductionService::getPreviousProducts($prod, $previousProducts, $unities, $updateStock);
        }

        return $previousProducts;
    }

    public static function getPreviousProducts(Product $product, &$previousProducts = [], $unities, $updateStock)
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

            if ($updateStock) {
                $previousProduct->update([
                    'necessary_stock' => $newUnities < 0 ? 0 : $newUnities
                ]);
            }

            NecessaryProductionService::getPreviousProducts($previousProduct, $previousProducts, $newUnities, $updateStock);
        }

        return $previousProducts;
    }

    public static function getResume(SaleOrder $saleOrder = null)
    {
        if ($saleOrder) {
            $orders = SaleOrder::where('is_active', true)->where('id', $saleOrder->id)->get();
        } else {
            $orders = SaleOrder::where('is_active', true)->get();
        }

        foreach ($orders as $order) {
            foreach ($order->products as $product) {
                $products[] = [
                    'product_id' => $product->id,
                    'name' => $product->name,
                    'quantity' => $product->pivot->quantity,
                ];
            }
        }

        $resume = collect($products)->groupBy('product_id')->map(function ($item) {
            return [
                'product_id' => $item->first()['product_id'],
                'name' => $item->first()['name'],
                'quantity' => $item->sum('quantity'),
            ];
        })->values()->toArray();

        return $resume;
    }
}
