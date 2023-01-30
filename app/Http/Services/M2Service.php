<?php

namespace App\Http\Services;

class M2Service
{
    public static function calculateSublotM2($sublot) {

        $product_m2 = $sublot->product->m2;
        $initial_quantity = $sublot->initial_quantity;
        $actual_quantity = $sublot->actual_quantity;

        $initial_m2 = $product_m2 * $initial_quantity;
        $actual_m2 = $product_m2 * $actual_quantity;

        $m2 = [
            'initial_m2' => $initial_m2,
            'actual_m2' => $actual_m2,
        ];

        return $m2;
    }

    public static function calculateM2($product_id, $quantity)
    {
        $product_m2 = \App\Models\Product::find($product_id)->m2;
        $m2 = $product_m2 * $quantity;

        return $m2;
    }
}
