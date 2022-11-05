<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Purchase;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PurchaseSeeder extends Seeder
{
    public function run()
    {
        Purchase::factory(5)->create();

        // Associate random products to each purchase
        Purchase::all()->each(function ($purchase) {

            for ($i = 0; $i < rand(1, 4); $i++) {

                // Random product where is_buyable = true and is not already associated to the purchase
                $product = Product::where('is_buyable', true)
                    ->whereDoesntHave('purchases', function ($query) use ($purchase) {
                        $query->where('purchase_id', $purchase->id);
                    })
                    ->inRandomOrder()
                    ->first();


                // $product = Product::where('is_buyable', true)->inRandomOrder()->first();

                $quantity = rand(20, 40);
                $price = $product->cost;

                $purchase->products()->attach($product->id, [
                    'quantity' => $quantity,
                    'price' => $price,
                    'subtotal' => $quantity * $price
                ]);
            }

            // Subtotal
            $subtotal = $purchase->products->sum(function ($product) {
                return $product->pivot->quantity * $product->pivot->price;
            });

            $purchase->subtotal = $subtotal;

            if ($purchase->supplier->iva_condition->discriminate) {
                $purchase->iva = $subtotal * 0.21;
            } else {
                $purchase->iva = 0;
            }
            $purchase->total = $purchase->subtotal + $purchase->iva;

            // Total
            $purchase->save();

            // Add 1 to total_purchases in supplier
            $supplier = $purchase->supplier;
            $supplier->total_purchases += 1;
            $supplier->save();
        });
    }
}
