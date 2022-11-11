<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Supplier;
use App\Models\Tendering;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TenderingSeeder extends Seeder
{

    public function run()
    {
        Tendering::factory(1)->create();

        // Associate random products to each tendering
        Tendering::all()->each(function ($tendering) {

            for ($i = 0; $i < rand(1, 4); $i++) {

                // Random product where is_sellable = true and is not already associated to the tendering
                $product = Product::where('is_buyable', true)
                    ->whereDoesntHave('tenderings', function ($query) use ($tendering) {
                        $query->where('tendering_id', $tendering->id);
                    })
                    ->inRandomOrder()
                    ->first();

                // $product = Product::where('is_buyable', true)->inRandomOrder()->first();

                $quantity = rand(50, 100);
                $price = $product->cost;

                $tendering->products()->attach($product->id, [
                    'quantity' => $quantity,
                    'price' => $price,
                    'subtotal' => $quantity * $price
                ]);
            }

            // Subtotal
            $subtotal = $tendering->products->sum(function ($product) {
                return $product->pivot->quantity * $product->pivot->price;
            });

            $tendering->subtotal = $subtotal;
            $tendering->iva = $subtotal * 0.21;
            $tendering->total = $tendering->subtotal + $tendering->iva;

            // Total
            $tendering->save();

            // -------------------------- CREACIÓN DE HASHES Y OFERTAS ----------------------------- //
            $suppliers = Supplier::where('active', true)->get();

            // Create a unique hash on hashes table for each supplier, so they can access the tendering
            foreach ($suppliers as $supplier) {

                $supplierHash = md5($supplier->id . $tendering->id . time());

                $supplier->hashes()->create([
                    'tendering_id' => $tendering->id,
                    'hash' => $supplierHash,
                    'sent_at' => now(),
                    'seen_at' => null,
                    'answered' => false,
                ]);

                $randomBoolean = rand(0, 1);
                if ($randomBoolean) {

                    $hash = $supplier->hashes()->where('hash', $supplierHash)->first();

                    $hash->update([
                        'seen' => true,
                        'seen_at' => now()->subMinutes(rand(10, 20))->subSeconds(rand(1, 59)),
                    ]);

                    $hash->offer()->create([
                        'subtotal' => 0,
                        'iva' => 0,
                        'total' => 0,
                        'delivery_date' => now(),
                        'observations' => '',
                    ]);

                    // Associate products of the tendering to the offer
                    foreach ($tendering->products as $product) {

                        if ($randomBoolean) {
                            $hash->offer->products()->attach($product->id, [
                                'quantity' => $quantity = rand($product->pivot->quantity - 5, $product->pivot->quantity),
                                'price' => $price =  rand($product->pivot->price - 50, $product->pivot->price + 50),
                                'subtotal' => $quantity * $price
                            ]);
                        }
                    }

                    // Subtotal
                    $subtotal = $hash->offer->products->sum(function ($product) {
                        return $product->pivot->quantity * $product->pivot->price;
                    });

                    $hash->offer->subtotal = $subtotal;
                    $hash->offer->iva = $subtotal * 0.21;
                    $hash->offer->total = $hash->offer->subtotal + $hash->offer->iva;

                    $hash->update([
                        'answered' => true,
                        'answered_at' => now()->subMinutes(rand(0, 10))->subSeconds(rand(1, 59)),
                    ]);

                    $hash->offer->save();
                }
                // -------------------------- FIN CREACIÓN DE HASHES Y OFERTAS ----------------------------- //
            }

            // ------------------------------ HASHES VISTOS SIN OFERTAS -------------------------------- //
            $unSeenHashes = $tendering->hashes()->where('seen', false)->get();

            foreach ($unSeenHashes as $unSeenHash) {
                $randomBoolean = rand(0, 1);
                if ($randomBoolean) {
                    $unSeenHash->update([
                        'seen' => true,
                        'seen_at' => now()->subMinutes(rand(10, 20))->subSeconds(rand(1, 59)),
                    ]);
                }
            }
            // --------------------------- FIN HASHES VISTOS SIN OFERTAS ------------------------------ //

            // --------------------------------- UN HASH CANCELADO --------------------------------- //
            $answeredHashes = $tendering->hashes()->where('answered', true)->get();

            $answeredHashes->random()->update([
                'cancelled' => true,
                'cancelled_at' => now()->subMinutes(rand(0, 10))->subSeconds(rand(1, 59)),
            ]);
            // ------------------------------- FIN UN HASH CANCELADO ------------------------------- //
        });
    }
}
