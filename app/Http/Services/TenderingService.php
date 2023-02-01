<?php

namespace App\Http\Services;

use App\Models\Product;

class TenderingService
{
    public static function create()
    {
        $products = Product::where('is_buyable', true)->where('real_stock', '<', 100)->get();
        $detail = [];

        foreach ($products as $product) {

            if ($product->real_stock < $product->minimum_stock - $product->reposition) {
                $reposition = $product->minimum_stock - $product->real_stock + $product->reposition;
            } else {
                $reposition = $product->reposition;
            }

            $detail[] = [
                'product_id' => $product->id,
                'reposition' => $reposition,
            ];
        }

    }
}
