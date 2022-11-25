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

            // 1) ROLLO 10
            [
                'product_name_id' => 1,
                'measure_id' => 25,
                'unity_id' => 1,
            ],

            // 2) ROLLO 11
            [
                'product_name_id' => 1,
                'measure_id' => 26,
                'unity_id' => 1,
            ],

            // 3) ROLLO 12
            [
                'product_name_id' => 1,
                'measure_id' => 27,
                'unity_id' => 1,
            ],

            // 4) ROLLO 13
            [
                'product_name_id' => 1,
                'measure_id' => 28,
                'unity_id' => 1,
            ],

            // 5) FAJA HÚMEDA 1/2 X 4 X 10
            [
                'product_name_id' => 2,
                'measure_id' => 21,
                'unity_id' => 1,
            ],

            // 6) FAJA SECA 1/2 X 4 X 10
            [
                'product_name_id' => 3,
                'measure_id' => 21,
                'unity_id' => 1,
            ],

            // 7) FAJA MACHIMBRADA 1/2 X 4 X 10
            [
                'product_name_id' => 4,
                'measure_id' => 21,
                'unity_id' => 1,
            ],

            // 8) PAQUETE GRANDE MACHIMBRADO 1/2 X 4 X 10
            [
                'product_name_id' => 5,
                'measure_id' => 21,
                'unity_id' => 3,
            ],

            // 9) PAQUETE GRANDE MACHIMBRADO 1/2 X 4 X 11
            [
                'product_name_id' => 5,
                'measure_id' => 22,
                'unity_id' => 3,
            ],

            // 10) FAJA HÚMEDA 1/2 X 4 X 12
            [
                'product_name_id' => 2,
                'measure_id' => 23,
                'unity_id' => 1,
            ],

            // 11) FAJA SECA 1/2 X 4 X 12
            [
                'product_name_id' => 3,
                'measure_id' => 23,
                'unity_id' => 1,
            ],

            // 12) FAJA MACHIMBRADA 1/2 X 4 X 12
            [
                'product_name_id' => 4,
                'measure_id' => 23,
                'unity_id' => 1,
            ],

            // 13) PAQUETE GRANDE MACHIMBRADO 1/2 X 4 X 12
            [
                'product_name_id' => 5,
                'measure_id' => 23,
                'unity_id' => 3,
            ],

            // 14) PAQUETE GRANDE MACHIMBRADO 1/2 X 4 X 13
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
