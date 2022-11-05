<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\PurchaseOrder;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PurchaseOrderSeeder extends Seeder
{
    public function run()
    {
        PurchaseOrder::factory(5)->create();

        // Associate random products to each purchase order
        PurchaseOrder::all()->each(function ($purchaseOrder) {

            for ($i = 0; $i < rand(1, 4); $i++) {

                // Random product where is_sellable = true and is not already associated to the purchase order
                $product = Product::where('is_buyable', true)
                    ->whereDoesntHave('purchaseOrders', function ($query) use ($purchaseOrder) {
                        $query->where('purchase_order_id', $purchaseOrder->id);
                    })
                    ->inRandomOrder()
                    ->first();

                // $product = Product::where('is_buyable', true)->inRandomOrder()->first();

                $quantity = rand(3, 5);
                $price = $product->cost;

                $purchaseOrder->products()->attach($product->id, [
                    'quantity' => $quantity,
                    'price' => $price,
                    'subtotal' => $quantity * $price
                ]);
            }

            // Subtotal
            $subtotal = $purchaseOrder->products->sum(function ($product) {
                return $product->pivot->quantity * $product->pivot->price;
            });

            $purchaseOrder->subtotal = $subtotal;

            if ($purchaseOrder->supplier->iva_condition->discriminate) {
                $purchaseOrder->iva = $subtotal * 0.21;
                $purchaseOrder->total = $purchaseOrder->subtotal + $purchaseOrder->iva;
            } else {
                $purchaseOrder->iva = 0;
                $purchaseOrder->total = $purchaseOrder->subtotal;
            }

            // Total
            $purchaseOrder->save();
        });
    }
}
