<?php

namespace Database\Seeders;

use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class Purchase2Seeder extends Seeder
{
    public function run()
    {

        // Find a supplier->iva_condition->discriminate = true
        $supplier = \App\Models\Supplier::whereHas('iva_condition', function ($query) {
            $query->where('discriminate', false);
        })->inRandomOrder()->first();

        if ($supplier == null) {
            return;
        }

        $date = Factory::create()->dateTimeBetween('-4 month', 'now');

        $createForm = [
            'user_id' => \App\Models\User::inRandomOrder()->first()->id,
            'date' => $date,
            'supplier_id' => $supplier->id,
            'supplier_order_id' => null,
            'payment_condition_id' => \App\Models\PaymentConditions::inRandomOrder()->first()->id,
            'payment_method_id' => \App\Models\PaymentMethods::inRandomOrder()->first()->id,
            'voucher_type_id' => \App\Models\VoucherTypes::inRandomOrder()->first()->id,
            'voucher_number' => rand(10000, 99999),
            'is_active' => true,
            'subtotal' => 0,
            'iva' => 0,
            'total' => 0,
            'observations' => 'Compra a proveedor que NO DISCRMINA IVA y de tipo DETALLADA',
            'type_of_purchase' => 1,
            'total_weight' => 0,
            'created_at' => $date,
            'updated_at' => $date
        ];

        $purchase = \App\Models\Purchase::create($createForm);

        $aux = rand(1, 4);

        // Get $aux products that are buyable and not repeated
        $products = \App\Models\Product::where('is_buyable', true)
            ->whereDoesntHave('purchaseOrders', function ($query) use ($purchase) {
                $query->where('purchase_order_id', $purchase->id);
            })
            ->inRandomOrder()
            ->take($aux)
            ->get();


        foreach ($products as $product) {

            $quantity = rand(15, 25);
            $tn_total = $quantity * 1.18;
            $tn_price = $product->cost;
            $subtotal = $tn_total * $tn_price;

            $purchase->products()->attach($product->id, [
                'quantity' => $quantity,
                'tn_total' => $tn_total,
                'tn_price' => $tn_price,
                'subtotal' => $subtotal
            ]);
        }

        // Subtotal
        $subtotal = $purchase->products->sum(function ($product) {
            return $product->pivot->subtotal;
        });

        // Total weight
        $total_weight = $purchase->products->sum(function ($product) {
            return $product->pivot->tn_total;
        });

        $purchase->subtotal = $subtotal;
        $purchase->iva = $subtotal * 0.21;
        $purchase->total = $purchase->subtotal + $purchase->iva;
        $purchase->total_weight = $total_weight;

        $purchase->save();
    }
}
