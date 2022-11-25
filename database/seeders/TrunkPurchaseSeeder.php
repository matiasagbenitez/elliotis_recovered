<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use App\Models\TrunkPurchase;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TrunkPurchaseSeeder extends Seeder
{
    public function run()
    {
        $purchases = \App\Models\Purchase::all();

        $i = 1;

        foreach ($purchases as $purchase) {
            if ($purchase->id <= 3) {
                $trunkPurchase = TrunkPurchase::create([
                    'purchase_id' => $purchase->id,
                    'code' => 'LR-' . $i++,
                ]);

                // Associate the same products of the puchase to the trunk purchase
                $trunkPurchase->trunk_lots()->createMany(
                    $purchase->products->map(function ($product) {
                        // Code is consecutive in the same trunk purchase
                        return [
                            'product_id' => $product->id,
                            // Random code integer
                            'code' => 'SLR-' . rand(1000, 9999),
                            'initial_quantity' => $product->pivot->quantity,
                            'actual_quantity' => $product->pivot->quantity,
                        ];
                    })
                );
            }
        }
    }
}
