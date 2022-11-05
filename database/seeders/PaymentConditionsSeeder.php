<?php

namespace Database\Seeders;

use App\Models\PaymentConditions;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentConditionsSeeder extends Seeder
{
    public function run()
    {
        $payment_conditions = [
            [
                'name' => 'Pago al contado',
                'is_deferred' => false,
            ],
            [
                'name' => 'Pago anticipado',
                'is_deferred' => false,
            ],
            [
                'name' => 'Pago aplazado',
                'is_deferred' => true,
            ]
        ];

        foreach ($payment_conditions as $payment_condition) {
            PaymentConditions::create($payment_condition);
        }
    }
}
