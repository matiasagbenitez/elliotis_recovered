<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\TrunkLot;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PurchaseSeeder extends Seeder
{
    public function run()
    {
        Purchase::factory(15)->create();

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

                $quantity = rand(40, 60);
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

            if ($purchase->id <= 13) {

                // Last code
                $lastCode = TrunkLot::latest('code')->first();

                if ($lastCode) {
                    $lastCode = $lastCode->code;
                } else {
                    $lastCode = 'LR-0000';
                }

                $codeNumber = (int) substr($lastCode, 3, 4);

                $trunkLot = TrunkLot::create([
                    'purchase_id' => $purchase->id,
                    'code' => 'LR-' . str_pad($codeNumber + 1, 4, '0', STR_PAD_LEFT),
                ]);

                $trunkLot->trunkSublots()->createMany(
                    $purchase->products->map(function ($product) {
                        return [
                            'product_id' => $product->id,
                            'area_id' => 1,
                            'initial_quantity' => $product->pivot->quantity,
                            'actual_quantity' => $product->pivot->quantity,
                        ];
                    })
                );

                $purchase->update([
                    'is_confirmed' => true,
                    'confirmed_by' => 1,
                    'confirmed_at' => now(),
                ]);
            }

            // Total
            $purchase->save();

            // Add 1 to total_purchases in supplier
            $supplier = $purchase->supplier;
            $supplier->total_purchases += 1;
            $supplier->save();
        });
    }
}
