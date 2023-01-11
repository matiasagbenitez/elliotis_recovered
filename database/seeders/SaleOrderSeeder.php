<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\SaleOrder;
use Illuminate\Database\Seeder;
use App\Http\Services\NecessaryProductionService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SaleOrderSeeder extends Seeder
{
    public function run()
    {
        SaleOrder::factory(3)->create();

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

                $quantity = rand(1, 4);
                $price = $product->selling_price;

                $saleOrder->products()->attach($product->id, [
                    'm2_unitary' => $product->m2,
                    'quantity' => $quantity,
                    'm2_total' => $product->m2 * $quantity,
                    'm2_price' => $product->m2_price,
                    'subtotal' => $product->m2_price * ($product->m2 * $quantity)
                ]);
            }

            // Subtotal
            $subtotal = $saleOrder->products->sum(function ($product) {
                return $product->pivot->subtotal;
            });

            $saleOrder->subtotal = $subtotal;
            $saleOrder->iva = $subtotal * 0.21;
            $saleOrder->total = $saleOrder->subtotal + $saleOrder->iva;

            // Total
            $saleOrder->save();

            // NecessaryProductionService::calculate(null, true);
        });

    }

}
