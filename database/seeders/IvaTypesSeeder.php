<?php

namespace Database\Seeders;

use App\Models\IvaTypes;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class IvaTypesSeeder extends Seeder
{
    public function run()
    {
        $iva_types = [
            [
                'name' => 'IVA general',
                'percentage' => 27,
            ],
            [
                'name' => 'IVA reducido I',
                'percentage' => 21,
            ],
            [
                'name' => 'IVA reducido II',
                'percentage' => 10.5,
            ],
            [
                'name' => 'IVA superreducido',
                'percentage' => 2.5,
            ],
            [
                'name' => 'IVA exento',
                'percentage' => 0,
            ],
        ];

        foreach ($iva_types as $iva_type) {
            IvaTypes::create($iva_type);
        }
    }
}
