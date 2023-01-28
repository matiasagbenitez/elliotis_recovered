<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\TrunkLot;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TrunkLotSeeder extends Seeder
{
    public function run()
    {
        $purchases = Purchase::where('is_active', true)->get();

        foreach ($purchases as $purchase) {
            {
                try {

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
                        'confirmed_at' => now(),
                        'confirmed_by' => 1
                    ]);

                    // Actualizamos el stock de los productos
                    foreach ($purchase->products as $product) {
                        $p = Product::find($product->id);
                        $p->update([
                            'real_stock' => $p->real_stock + $product->pivot->quantity
                        ]);
                    }

                } catch (\Exception $e) {
                    dd($e);
                }
            }
        }

    }
}
