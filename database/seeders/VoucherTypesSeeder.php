<?php

namespace Database\Seeders;

use App\Models\VoucherTypes;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class VoucherTypesSeeder extends Seeder
{
    public function run()
    {
        $voucher_types = [
            [
                'name' => 'Factura A',
            ],
            [
                'name' => 'Factura B',
            ],
            [
                'name' => 'Factura C',
            ],
            [
                'name' => 'Nota de crédito',
            ],
            [
                'name' => 'Nota de débito',
            ],
            [
                'name' => 'Ticket',
            ],
            [
                'name' => 'Orden de compra',
            ],
            [
                'name' => 'Remito',
            ],
            [
                'name' => 'Otro',
            ]
        ];

        foreach ($voucher_types as $voucher_type) {
            VoucherTypes::create($voucher_type);
        }
    }
}
