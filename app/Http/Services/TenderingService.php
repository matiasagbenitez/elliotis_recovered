<?php

namespace App\Http\Services;

use App\Models\User;
use App\Models\Product;
use App\Notifications\NewTenderingRequired;

class TenderingService
{
    public static function create($task_id, $task_name)
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

        // Notificar al usuario 1
        $user = User::find(1);
        $user->notify(new NewTenderingRequired($task_id, $task_name, $detail));

    }
}
