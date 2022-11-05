<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\SaleOrder;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SaleOrderSeeder extends Seeder
{
    public function run()
    {
        SaleOrder::factory(5)->create();

        // Associate random products to each sale order
        SaleOrder::all()->each(function ($saleOrder) {

            for ($i = 0; $i < rand(1, 4); $i++) {

                // Random product where is_sellable = true and is not already associated to the sale order
                $product = Product::where('is_salable', true)
                    ->whereDoesntHave('saleOrders', function ($query) use ($saleOrder) {
                        $query->where('sale_order_id', $saleOrder->id);
                    })
                    ->inRandomOrder()
                    ->first();

                // $product = Product::where('is_salable', true)->inRandomOrder()->first();

                $quantity = rand(3, 5);
                $price = $product->selling_price;

                $saleOrder->products()->attach($product->id, [
                    'quantity' => $quantity,
                    'price' => $price,
                    'subtotal' => $quantity * $price
                ]);
            }

            // Subtotal
            $subtotal = $saleOrder->products->sum(function ($product) {
                return $product->pivot->quantity * $product->pivot->price;
            });

            $saleOrder->subtotal = $subtotal;
            $saleOrder->iva = $subtotal * 0.21;
            $saleOrder->total = $saleOrder->subtotal + $saleOrder->iva;

            // Total
            $saleOrder->save();
        });

    }
}
