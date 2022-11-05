<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductNameSeeder extends Seeder
{
    public function run()
    {
        $product_names = [
            [
                'name' => 'Rollo',
            ],
            [
                'name' => 'Faja húmeda',
            ],
            [
                'name' => 'Faja seca',
            ],
            [
                'name' => 'Faja machimbrada',
            ],
            [
                'name' => 'Paquete grande machimbrado',
            ]
        ];

        foreach ($product_names as $product_name) {
            \App\Models\ProductName::create($product_name);
        }
    }
}
