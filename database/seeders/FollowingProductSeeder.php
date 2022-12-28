<?php

namespace Database\Seeders;

use App\Models\FollowingProduct;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class FollowingProductSeeder extends Seeder
{
    public function run()
    {
        $items = [
            // ROLLOS A FAJAS HÚMEDAS
            [
                'base_product_id' => 1,
                'following_product_id' => 5,
            ],
            [
                'base_product_id' => 2,
                'following_product_id' => 5,
            ],
            [
                'base_product_id' => 2,
                'following_product_id' => 6,
            ],
            [
                'base_product_id' => 3,
                'following_product_id' => 5,
            ],
            [
                'base_product_id' => 3,
                'following_product_id' => 6,
            ],
            [
                'base_product_id' => 3,
                'following_product_id' => 7,
            ],
            [
                'base_product_id' => 4,
                'following_product_id' => 5,
            ],
            [
                'base_product_id' => 4,
                'following_product_id' => 6,
            ],
            [
                'base_product_id' => 4,
                'following_product_id' => 7,
            ],
            [
                'base_product_id' => 4,
                'following_product_id' => 8,
            ],

            // FAJAS HÚMEDAS A FAJAS SECAS (uno a uno)
            [
                'base_product_id' => 5,
                'following_product_id' => 9,
            ],
            [
                'base_product_id' => 6,
                'following_product_id' => 10,
            ],
            [
                'base_product_id' => 7,
                'following_product_id' => 11,
            ],
            [
                'base_product_id' => 8,
                'following_product_id' => 12,
            ],

            // FAJAS SECAS A MACHIMBRADAS
            [
                'base_product_id' => 9,
                'following_product_id' => 13,
            ],
            [
                'base_product_id' => 10,
                'following_product_id' => 13,
            ],
            [
                'base_product_id' => 10,
                'following_product_id' => 14,
            ],
            [
                'base_product_id' => 11,
                'following_product_id' => 13,
            ],
            [
                'base_product_id' => 11,
                'following_product_id' => 14,
            ],
            [
                'base_product_id' => 11,
                'following_product_id' => 15,
            ],
            [
                'base_product_id' => 12,
                'following_product_id' => 13,
            ],
            [
                'base_product_id' => 12,
                'following_product_id' => 14,
            ],
            [
                'base_product_id' => 12,
                'following_product_id' => 15,
            ],
            [
                'base_product_id' => 12,
                'following_product_id' => 16,
            ],

            // FAJAS MACHIMBRADAS A PAQUETES
            [
                'base_product_id' => 13,
                'following_product_id' => 17,
                'final_product' => true,
            ],
            [
                'base_product_id' => 14,
                'following_product_id' => 18,
                'final_product' => true,
            ],
            [
                'base_product_id' => 15,
                'following_product_id' => 19,
                'final_product' => true,
            ],
            [
                'base_product_id' => 16,
                'following_product_id' => 20,
                'final_product' => true,
            ]
        ];

        foreach ($items as $item) {
            $product = Product::find($item['base_product_id']);
            $followingProduct = Product::find($item['following_product_id']);
            $product->followingProducts()->attach($followingProduct);
        }

    }
}
