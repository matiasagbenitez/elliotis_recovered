<?php

namespace Database\Seeders;

use App\Models\ProductType;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $products = [

            // ROLLO  DE 10
            [
                'name' => ProductType::find(1)->product_name->name . ' ' . ProductType::find(1)->measure->name . ' (pino elliotis)',
                'product_type_id' => 1,
                'wood_type_id' => 1,
                'is_buyable' => true,
                'real_stock' => 120,
                'minimum_stock' => 100,
                'reposition' => 50,
                'iva_type_id' => 2,
                'cost' => 1500,
                'margin' => ProductType::find(1)->product_name->margin,
                'selling_price' => 1500 * ProductType::find(1)->product_name->margin,
            ],

            // ROLLO DE 11
            [
                'name' => ProductType::find(2)->product_name->name . ' ' . ProductType::find(2)->measure->name . ' (pino elliotis)',
                'product_type_id' => 2,
                'wood_type_id' => 1,
                'is_buyable' => true,
                'real_stock' => 80,
                'minimum_stock' => 100,
                'reposition' => 50,
                'iva_type_id' => 2,
                'cost' => 1700,
                'margin' => ProductType::find(2)->product_name->margin,
                'selling_price' => 1700 * ProductType::find(2)->product_name->margin,
            ],

            // ROLLO DE 12
            [
                'name' => ProductType::find(3)->product_name->name . ' ' . ProductType::find(3)->measure->name . ' (pino elliotis)',
                'product_type_id' => 3,
                'wood_type_id' => 1,
                'is_buyable' => true,
                'real_stock' => 110,
                'minimum_stock' => 100,
                'reposition' => 50,
                'iva_type_id' => 2,
                'cost' => 1800,
                'margin' => ProductType::find(3)->product_name->margin,
                'selling_price' => 1800 * ProductType::find(3)->product_name->margin,
            ],

            // ROLLO DE 13
            [
                'name' => ProductType::find(4)->product_name->name . ' ' . ProductType::find(4)->measure->name . ' (pino elliotis)',
                'product_type_id' => 4,
                'wood_type_id' => 1,
                'is_buyable' => true,
                'real_stock' => 90,
                'minimum_stock' => 100,
                'reposition' => 50,
                'iva_type_id' => 2,
                'cost' => 2000,
                'margin' => ProductType::find(4)->product_name->margin,
                'selling_price' => 2000 * ProductType::find(4)->product_name->margin,
            ],

            // FAJA HÚMEDA DE 10
            [
                'name' => ProductType::find(5)->product_name->name . ' ' . ProductType::find(5)->measure->name . ' (pino elliotis)',
                'product_type_id' => 5,
                'wood_type_id' => 1,
                'real_stock' => 1500,
                'minimum_stock' => 1000,
                'reposition' => 500,
                'iva_type_id' => 2,
                'cost' => 120,
                'margin' => ProductType::find(5)->product_name->margin,
                'selling_price' => 120 * ProductType::find(5)->product_name->margin,
            ],

            // FAJA HÚMEDA DE 11
            [
                'name' => ProductType::find(6)->product_name->name . ' ' . ProductType::find(6)->measure->name . ' (pino elliotis)',
                'product_type_id' => 6,
                'wood_type_id' => 1,
                'real_stock' => 1500,
                'minimum_stock' => 1000,
                'reposition' => 500,
                'iva_type_id' => 2,
                'cost' => 140,
                'margin' => ProductType::find(6)->product_name->margin,
                'selling_price' => 140 * ProductType::find(6)->product_name->margin,
            ],

            // FAJA HÚMEDA DE 12
            [
                'name' => ProductType::find(7)->product_name->name . ' ' . ProductType::find(7)->measure->name . ' (pino elliotis)',
                'product_type_id' => 7,
                'wood_type_id' => 1,
                'real_stock' => 1500,
                'minimum_stock' => 1000,
                'reposition' => 500,
                'iva_type_id' => 2,
                'cost' => 160,
                'margin' => ProductType::find(7)->product_name->margin,
                'selling_price' => 160 * ProductType::find(7)->product_name->margin,
            ],

            // FAJA HÚMEDA DE 13
            [
                'name' => ProductType::find(8)->product_name->name . ' ' . ProductType::find(8)->measure->name . ' (pino elliotis)',
                'product_type_id' => 8,
                'wood_type_id' => 1,
                'real_stock' => 1500,
                'minimum_stock' => 1000,
                'reposition' => 500,
                'iva_type_id' => 2,
                'cost' => 180,
                'margin' => ProductType::find(8)->product_name->margin,
                'selling_price' => 180 * ProductType::find(8)->product_name->margin,
            ],

            // FAJA SECA DE 10
            [
                'name' => ProductType::find(9)->product_name->name . ' ' . ProductType::find(9)->measure->name . ' (pino elliotis)',
                'product_type_id' => 9,
                'wood_type_id' => 1,
                'real_stock' => 2500,
                'minimum_stock' => 1000,
                'reposition' => 500,
                'iva_type_id' => 2,
                'cost' => 140,
                'margin' => ProductType::find(9)->product_name->margin,
                'selling_price' => 140 * ProductType::find(9)->product_name->margin,
            ],

            // FAJA SECA DE 11
            [
                'name' => ProductType::find(10)->product_name->name . ' ' . ProductType::find(10)->measure->name . ' (pino elliotis)',
                'product_type_id' => 10,
                'wood_type_id' => 1,
                'real_stock' => 2500,
                'minimum_stock' => 1000,
                'reposition' => 500,
                'iva_type_id' => 2,
                'cost' => 160,
                'margin' => ProductType::find(10)->product_name->margin,
                'selling_price' => 160 * ProductType::find(10)->product_name->margin,
            ],

            // FAJA SECA DE 12
            [
                'name' => ProductType::find(11)->product_name->name . ' ' . ProductType::find(11)->measure->name . ' (pino elliotis)',
                'product_type_id' => 11,
                'wood_type_id' => 1,
                'real_stock' => 2500,
                'minimum_stock' => 1000,
                'reposition' => 500,
                'iva_type_id' => 2,
                'cost' => 180,
                'margin' => ProductType::find(11)->product_name->margin,
                'selling_price' => 180 * ProductType::find(11)->product_name->margin,
            ],

            // FAJA SECA DE 13
            [
                'name' => ProductType::find(12)->product_name->name . ' ' . ProductType::find(12)->measure->name . ' (pino elliotis)',
                'product_type_id' => 12,
                'wood_type_id' => 1,
                'real_stock' => 2500,
                'minimum_stock' => 1000,
                'reposition' => 500,
                'iva_type_id' => 2,
                'cost' => 200,
                'margin' => ProductType::find(12)->product_name->margin,
                'selling_price' => 200 * ProductType::find(12)->product_name->margin,
            ],

            // FAJA MACHIMBRADA DE 10
            [
                'name' => ProductType::find(13)->product_name->name . ' ' . ProductType::find(13)->measure->name . ' (pino elliotis)',
                'product_type_id' => 13,
                'wood_type_id' => 1,
                'real_stock' => 200,
                'minimum_stock' => 300,
                'reposition' => 500,
                'iva_type_id' => 2,
                'cost' => 160,
                'margin' => ProductType::find(13)->product_name->margin,
                'selling_price' => 160 * ProductType::find(13)->product_name->margin,
            ],

            // FAJA MACHIMBRADA DE 11
            [
                'name' => ProductType::find(14)->product_name->name . ' ' . ProductType::find(14)->measure->name . ' (pino elliotis)',
                'product_type_id' => 14,
                'wood_type_id' => 1,
                'real_stock' => 200,
                'minimum_stock' => 300,
                'reposition' => 500,
                'iva_type_id' => 2,
                'cost' => 180,
                'margin' => ProductType::find(14)->product_name->margin,
                'selling_price' => 180 * ProductType::find(14)->product_name->margin,
            ],

            // FAJA MACHIMBRADA DE 12
            [
                'name' => ProductType::find(15)->product_name->name . ' ' . ProductType::find(15)->measure->name . ' (pino elliotis)',
                'product_type_id' => 15,
                'wood_type_id' => 1,
                'real_stock' => 200,
                'minimum_stock' => 300,
                'reposition' => 500,
                'iva_type_id' => 2,
                'cost' => 200,
                'margin' => ProductType::find(15)->product_name->margin,
                'selling_price' => 200 * ProductType::find(15)->product_name->margin,
            ],

            // FAJA MACHIMBRADA DE 13
            [
                'name' => ProductType::find(16)->product_name->name . ' ' . ProductType::find(16)->measure->name . ' (pino elliotis)',
                'product_type_id' => 16,
                'wood_type_id' => 1,
                'real_stock' => 200,
                'minimum_stock' => 300,
                'reposition' => 500,
                'iva_type_id' => 2,
                'cost' => 220,
                'margin' => ProductType::find(16)->product_name->margin,
                'selling_price' => 220 * ProductType::find(16)->product_name->margin,
            ],

            // PAQUETE GRANDE MACHIMBRADO DE 10
            [
                'name' => ProductType::find(17)->product_name->name . ' ' . ProductType::find(17)->measure->name . ' (pino elliotis)',
                'product_type_id' => 17,
                'wood_type_id' => 1,
                'is_salable' => true,
                'real_stock' => 15,
                'minimum_stock' => 4,
                'reposition' => 4,
                'iva_type_id' => 2,
                'cost' => 150000,
                'margin' => ProductType::find(17)->product_name->margin,
                'selling_price' => 150000 * ProductType::find(17)->product_name->margin,
            ],

            // PAQUETE GRANDE MACHIMBRADO DE 11
            [
                'name' => ProductType::find(18)->product_name->name . ' ' . ProductType::find(18)->measure->name . ' (pino elliotis)',
                'product_type_id' => 18,
                'wood_type_id' => 1,
                'is_salable' => true,
                'real_stock' => 15,
                'minimum_stock' => 4,
                'reposition' => 4,
                'iva_type_id' => 2,
                'cost' => 160000,
                'margin' => ProductType::find(18)->product_name->margin,
                'selling_price' => 160000 * ProductType::find(18)->product_name->margin,
            ],

            // PAQUETE GRANDE MACHIMBRADO DE 12
            [
                'name' => ProductType::find(19)->product_name->name . ' ' . ProductType::find(19)->measure->name . ' (pino elliotis)',
                'product_type_id' => 19,
                'wood_type_id' => 1,
                'is_salable' => true,
                'real_stock' => 15,
                'minimum_stock' => 4,
                'reposition' => 4,
                'iva_type_id' => 2,
                'cost' => 170000,
                'margin' => ProductType::find(19)->product_name->margin,
                'selling_price' => 170000 * ProductType::find(19)->product_name->margin,
            ],

            // PAQUETE GRANDE MACHIMBRADO DE 13
            [
                'name' => ProductType::find(20)->product_name->name . ' ' . ProductType::find(20)->measure->name . ' (pino elliotis)',
                'product_type_id' => 20,
                'wood_type_id' => 1,
                'is_salable' => true,
                'real_stock' => 15,
                'minimum_stock' => 4,
                'reposition' => 4,
                'iva_type_id' => 2,
                'cost' => 180000,
                'margin' => ProductType::find(20)->product_name->margin,
                'selling_price' => 180000 * ProductType::find(20)->product_name->margin,
            ],

        ];

        foreach ($products as $product) {
            \App\Models\Product::create($product);
        }
    }
}
