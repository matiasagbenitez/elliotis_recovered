<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Date;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PurchaseOrder3Seeder extends Seeder
{
    public function run()
    {
        // Orden de compra para proveedor que DISCRMINA IVA y de tipo MIXTA

        // Find a supplier->iva_condition->discriminate = true
        $supplier = \App\Models\Supplier::whereHas('iva_condition', function ($query) {
            $query->where('discriminate', true);
        })->first();

        if ($supplier == null) {
            return;
        }

        $createForm = [
            'user_id' => \App\Models\User::inRandomOrder()->first()->id,
            'supplier_id' => $supplier->id,
            'registration_date' => Date::now(),
            'is_active' => true,
            'subtotal' => 0,
            'iva' => 0,
            'total' => 0,
            'observations' => 'Orden de compra para proveedor que DISCRMINA IVA y de tipo MIXTA',
            'type_of_purchase' => 2,
            'total_weight' => 0,
        ];

        $purchaseOrder = \App\Models\PurchaseOrder::create($createForm);

        for ($i = 0; $i < rand(1, 4); $i++) {

            $product = \App\Models\Product::where('is_buyable', true)
                ->whereDoesntHave('purchaseOrders', function ($query) use ($purchaseOrder) {
                    $query->where('purchase_order_id', $purchaseOrder->id);
                })
                ->inRandomOrder()
                ->first();

            $quantity = rand(18, 30);
            $tn_total = $quantity * 1.18;
            $tn_price = 3000;
            $subtotal = $tn_total * $tn_price;

            $purchaseOrder->products()->attach($product->id, [
                'quantity' => $quantity,
                'tn_total' => $tn_total,
                'tn_price' => $tn_price,
                'subtotal' => $subtotal
            ]);
        }

        // Subtotal
        $subtotal = $purchaseOrder->products->sum(function ($product) {
            return $product->pivot->subtotal;
        });

        // Total weight
        $total_weight = $purchaseOrder->products->sum(function ($product) {
            return $product->pivot->tn_total;
        });

        $purchaseOrder->subtotal = $subtotal;
        $purchaseOrder->iva = $subtotal * 0.21;
        $purchaseOrder->total = $purchaseOrder->subtotal + $purchaseOrder->iva;
        $purchaseOrder->total_weight = $total_weight;

        $purchaseOrder->save();
    }
}
