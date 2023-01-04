<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PreviousProductSeeder extends Seeder
{
    public function run()
    {
        $previous_products = [

            // 10
            [
                'product_id' => 9,
                'previous_product_id' => 5,
            ],
            [
                'product_id' => 13,
                'previous_product_id' => 9,
            ],
            [
                'product_id' => 17,
                'previous_product_id' => 13,
            ],

            // 11
            [
                'product_id' => 10,
                'previous_product_id' => 6,
            ],
            [
                'product_id' => 14,
                'previous_product_id' => 10,
            ],
            [
                'product_id' => 18,
                'previous_product_id' => 14,
            ],


            // 12
            [
                'product_id' => 11,
                'previous_product_id' => 7,
            ],
            [
                'product_id' => 15,
                'previous_product_id' => 11,
            ],
            [
                'product_id' => 19,
                'previous_product_id' => 15,
            ],

            // 13
            [
                'product_id' => 12,
                'previous_product_id' => 8,
            ],
            [
                'product_id' => 16,
                'previous_product_id' => 12,
            ],
            [
                'product_id' => 20,
                'previous_product_id' => 16,
            ],

        ];

        foreach ($previous_products as $previous_product) {
            \App\Models\PreviousProduct::create($previous_product);
        }
    }
}
