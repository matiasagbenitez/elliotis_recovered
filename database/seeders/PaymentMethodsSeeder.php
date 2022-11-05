<?php

namespace Database\Seeders;

use App\Models\PaymentMethods;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PaymentMethodsSeeder extends Seeder
{
    public function run()
    {
        $payment_methods = [
            [
                'name' => 'Efectivo',
            ],
            [
                'name' => 'Transferencia',
            ],
            [
                'name' => 'Cheque',
            ],
            [
                'name' => 'Tarjeta de crédito',
            ],
            [
                'name' => 'Tarjeta de débito',
            ],
            [
                'name' => 'Mercado Pago',
            ],
            [
                'name' => 'Paypal',
            ],
            [
                'name' => 'Otro',
            ],
        ];

        foreach ($payment_methods as $payment_method) {
            PaymentMethods::create($payment_method);
        }
    }
}
