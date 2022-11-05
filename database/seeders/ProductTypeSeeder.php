<?php

namespace Database\Seeders;

use App\Models\Measure;
use App\Models\ProductType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductTypeSeeder extends Seeder
{
    public function run()
    {
        $productTypes = [
            [
                'product_name_id' => 1,
                'measure_id' => 25,
                'unity_id' => 1,
            ],
            [
                'product_name_id' => 2,
                'measure_id' => 21,
                'unity_id' => 1,
            ],
            [
                'product_name_id' => 3,
                'measure_id' => 21,
                'unity_id' => 1,
            ],
            [
                'product_name_id' => 4,
                'measure_id' => 21,
                'unity_id' => 1,
            ],
            [
                'product_name_id' => 5,
                'measure_id' => 21,
                'unity_id' => 3,
            ],
            [
                'product_name_id' => 1,
                'measure_id' => 27,
                'unity_id' => 1,
            ],
            [
                'product_name_id' => 2,
                'measure_id' => 23,
                'unity_id' => 1,
            ],
            [
                'product_name_id' => 3,
                'measure_id' => 23,
                'unity_id' => 1,
            ],
            [
                'product_name_id' => 4,
                'measure_id' => 23,
                'unity_id' => 1,
            ],
            [
                'product_name_id' => 5,
                'measure_id' => 23,
                'unity_id' => 3,
            ],

            // Rollos 11 y 13

            [
                'product_name_id' => 1,
                'measure_id' => 26,
                'unity_id' => 1,
            ],
            [
                'product_name_id' => 1,
                'measure_id' => 28,
                'unity_id' => 1,
            ],

            // PAQUETES GRANDES MACHIMBRADOS DE 11' Y 13'
            [
                'product_name_id' => 5,
                'measure_id' => 22,
                'unity_id' => 3,
            ],
            [
                'product_name_id' => 5,
                'measure_id' => 24,
                'unity_id' => 3,
            ],

        ];

        foreach ($productTypes as $productType) {
            $productType = ProductType::create($productType);
        }
    }
}
