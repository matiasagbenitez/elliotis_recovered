<?php

namespace Database\Seeders;

use App\Models\Sale;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SaleSeeder extends Seeder
{
    public function run()
    {
        Sale::factory(5)->create();

        // Associate random products to each sale
        Sale::all()->each(function ($sale) {

            for ($i = 0; $i < rand(1, 4); $i++) {

                // Random product where is_sellable = true and is not already associated to the sale
                $product = Product::where('is_salable', true)
                    ->whereDoesntHave('sales', function ($query) use ($sale) {
                        $query->where('sale_id', $sale->id);
                    })
                    ->inRandomOrder()
                    ->first();

                // $product = Product::where('is_salable', true)->inRandomOrder()->first();

                $quantity = rand(3, 5);
                $price = $product->selling_price;

                $sale->products()->attach($product->id, [
                    'quantity' => $quantity,
                    'price' => $price,
                    'subtotal' => $quantity * $price
                ]);
            }

            // Subtotal
            $subtotal = $sale->products->sum(function ($product) {
                return $product->pivot->quantity * $product->pivot->price;
            });

            $sale->subtotal = $subtotal;
            $sale->iva = $subtotal * 0.21;
            $sale->total = $sale->subtotal + $sale->iva;

            // Total
            $sale->save();

            // Add 1 to total_sales in client
            $client = $sale->client;
            $client->total_sales += 1;
            $client->save();
        });
    }
}
