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
                'phase_id' => 1,
                'wood_type_id' => 1,
                'is_buyable' => true,
                'real_stock' => 0,
                'minimum_stock' => 100,
                'iva_type_id' => 2,
                'cost' => 2500,
                'margin' => ProductType::find(1)->product_name->margin,
                'selling_price' => 2500 * ProductType::find(1)->product_name->margin,
            ],

            // ROLLO DE 11
            [
                'name' => ProductType::find(2)->product_name->name . ' ' . ProductType::find(2)->measure->name . ' (pino elliotis)',
                'product_type_id' => 2,
                'phase_id' => 1,
                'wood_type_id' => 1,
                'is_buyable' => true,
                'real_stock' => 0,
                'minimum_stock' => 100,
                'iva_type_id' => 2,
                'cost' => 2700,
                'margin' => ProductType::find(2)->product_name->margin,
                'selling_price' => 2700 * ProductType::find(2)->product_name->margin,
            ],

            // ROLLO DE 12
            [
                'name' => ProductType::find(3)->product_name->name . ' ' . ProductType::find(3)->measure->name . ' (pino elliotis)',
                'product_type_id' => 3,
                'phase_id' => 1,
                'wood_type_id' => 1,
                'is_buyable' => true,
                'real_stock' => 0,
                'minimum_stock' => 100,
                'iva_type_id' => 2,
                'cost' => 2800,
                'margin' => ProductType::find(3)->product_name->margin,
                'selling_price' => 2800 * ProductType::find(3)->product_name->margin,
            ],

            // ROLLO DE 13
            [
                'name' => ProductType::find(4)->product_name->name . ' ' . ProductType::find(4)->measure->name . ' (pino elliotis)',
                'product_type_id' => 4,
                'phase_id' => 1,
                'wood_type_id' => 1,
                'is_buyable' => true,
                'real_stock' => 0,
                'minimum_stock' => 100,
                'iva_type_id' => 2,
                'cost' => 3000,
                'margin' => ProductType::find(4)->product_name->margin,
                'selling_price' => 3000 * ProductType::find(4)->product_name->margin,
            ],

            // FAJA HÚMEDA DE 10
            [
                'name' => ProductType::find(5)->product_name->name . ' ' . ProductType::find(5)->measure->name . ' (pino elliotis)',
                'product_type_id' => 5,
                'phase_id' => 2,
                'm2' => ProductType::find(5)->measure->m2 * ProductType::find(5)->unity->unities,
                'm2_price' => 500,
                'wood_type_id' => 1,
                'real_stock' => 0,
                'minimum_stock' => 1000,
                'iva_type_id' => 2,
                'cost' => 120,
                'margin' => ProductType::find(5)->product_name->margin,
                'selling_price' => 120 * ProductType::find(5)->product_name->margin,
            ],

            // FAJA HÚMEDA DE 11
            [
                'name' => ProductType::find(6)->product_name->name . ' ' . ProductType::find(6)->measure->name . ' (pino elliotis)',
                'product_type_id' => 6,
                'phase_id' => 2,
                'm2' => ProductType::find(6)->measure->m2 * ProductType::find(6)->unity->unities,
                'm2_price' => 520,
                'wood_type_id' => 1,
                'real_stock' => 0,
                'minimum_stock' => 1000,
                'iva_type_id' => 2,
                'cost' => 140,
                'margin' => ProductType::find(6)->product_name->margin,
                'selling_price' => 140 * ProductType::find(6)->product_name->margin,
            ],

            // FAJA HÚMEDA DE 12
            [
                'name' => ProductType::find(7)->product_name->name . ' ' . ProductType::find(7)->measure->name . ' (pino elliotis)',
                'product_type_id' => 7,
                'phase_id' => 2,
                'm2' => ProductType::find(7)->measure->m2 * ProductType::find(7)->unity->unities,
                'm2_price' => 530,
                'wood_type_id' => 1,
                'real_stock' => 0,
                'minimum_stock' => 1000,
                'iva_type_id' => 2,
                'cost' => 160,
                'margin' => ProductType::find(7)->product_name->margin,
                'selling_price' => 160 * ProductType::find(7)->product_name->margin,
            ],

            // FAJA HÚMEDA DE 13
            [
                'name' => ProductType::find(8)->product_name->name . ' ' . ProductType::find(8)->measure->name . ' (pino elliotis)',
                'product_type_id' => 8,
                'phase_id' => 2,
                'm2' => ProductType::find(8)->measure->m2 * ProductType::find(8)->unity->unities,
                'm2_price' => 550,
                'wood_type_id' => 1,
                'real_stock' => 0,
                'minimum_stock' => 1000,
                'iva_type_id' => 2,
                'cost' => 180,
                'margin' => ProductType::find(8)->product_name->margin,
                'selling_price' => 180 * ProductType::find(8)->product_name->margin,
            ],

            // FAJA SECA DE 10
            [
                'name' => ProductType::find(9)->product_name->name . ' ' . ProductType::find(9)->measure->name . ' (pino elliotis)',
                'product_type_id' => 9,
                'phase_id' => 3,
                'm2' => ProductType::find(9)->measure->m2 * ProductType::find(9)->unity->unities,
                'm2_price' => 530,
                'wood_type_id' => 1,
                'real_stock' => 0,
                'minimum_stock' => 1000,
                'iva_type_id' => 2,
                'cost' => 140,
                'margin' => ProductType::find(9)->product_name->margin,
                'selling_price' => 140 * ProductType::find(9)->product_name->margin,
            ],

            // FAJA SECA DE 11
            [
                'name' => ProductType::find(10)->product_name->name . ' ' . ProductType::find(10)->measure->name . ' (pino elliotis)',
                'product_type_id' => 10,
                'phase_id' => 3,
                'm2' => ProductType::find(10)->measure->m2 * ProductType::find(10)->unity->unities,
                'm2_price' => 550,
                'wood_type_id' => 1,
                'real_stock' => 0,
                'minimum_stock' => 1000,
                'iva_type_id' => 2,
                'cost' => 160,
                'margin' => ProductType::find(10)->product_name->margin,
                'selling_price' => 160 * ProductType::find(10)->product_name->margin,
            ],

            // FAJA SECA DE 12
            [
                'name' => ProductType::find(11)->product_name->name . ' ' . ProductType::find(11)->measure->name . ' (pino elliotis)',
                'product_type_id' => 11,
                'phase_id' => 3,
                'm2' => ProductType::find(11)->measure->m2 * ProductType::find(11)->unity->unities,
                'm2_price' => 560,
                'wood_type_id' => 1,
                'real_stock' => 0,
                'minimum_stock' => 1000,
                'iva_type_id' => 2,
                'cost' => 180,
                'margin' => ProductType::find(11)->product_name->margin,
                'selling_price' => 180 * ProductType::find(11)->product_name->margin,
            ],

            // FAJA SECA DE 13
            [
                'name' => ProductType::find(12)->product_name->name . ' ' . ProductType::find(12)->measure->name . ' (pino elliotis)',
                'product_type_id' => 12,
                'phase_id' => 3,
                'm2' => ProductType::find(12)->measure->m2 * ProductType::find(12)->unity->unities,
                'm2_price' => 580,
                'wood_type_id' => 1,
                'real_stock' => 0,
                'minimum_stock' => 1000,
                'iva_type_id' => 2,
                'cost' => 200,
                'margin' => ProductType::find(12)->product_name->margin,
                'selling_price' => 200 * ProductType::find(12)->product_name->margin,
            ],

            // FAJA MACHIMBRADA DE 10
            [
                'name' => ProductType::find(13)->product_name->name . ' ' . ProductType::find(13)->measure->name . ' (pino elliotis)',
                'product_type_id' => 13,
                'phase_id' => 4,
                'm2' => ProductType::find(13)->measure->m2 * ProductType::find(13)->unity->unities,
                'm2_price' => 560,
                'wood_type_id' => 1,
                'real_stock' => 0,
                'minimum_stock' => 300,
                'iva_type_id' => 2,
                'cost' => 160,
                'margin' => ProductType::find(13)->product_name->margin,
                'selling_price' => 160 * ProductType::find(13)->product_name->margin,
            ],

            // FAJA MACHIMBRADA DE 11
            [
                'name' => ProductType::find(14)->product_name->name . ' ' . ProductType::find(14)->measure->name . ' (pino elliotis)',
                'product_type_id' => 14,
                'phase_id' => 4,
                'm2' => ProductType::find(14)->measure->m2 * ProductType::find(14)->unity->unities,
                'm2_price' => 580,
                'wood_type_id' => 1,
                'real_stock' => 0,
                'minimum_stock' => 300,
                'iva_type_id' => 2,
                'cost' => 180,
                'margin' => ProductType::find(14)->product_name->margin,
                'selling_price' => 180 * ProductType::find(14)->product_name->margin,
            ],

            // FAJA MACHIMBRADA DE 12
            [
                'name' => ProductType::find(15)->product_name->name . ' ' . ProductType::find(15)->measure->name . ' (pino elliotis)',
                'product_type_id' => 15,
                'phase_id' => 4,
                'm2' => ProductType::find(15)->measure->m2 * ProductType::find(15)->unity->unities,
                'm2_price' => 590,
                'wood_type_id' => 1,
                'real_stock' => 0,
                'minimum_stock' => 300,
                'iva_type_id' => 2,
                'cost' => 200,
                'margin' => ProductType::find(15)->product_name->margin,
                'selling_price' => 200 * ProductType::find(15)->product_name->margin,
            ],

            // FAJA MACHIMBRADA DE 13
            [
                'name' => ProductType::find(16)->product_name->name . ' ' . ProductType::find(16)->measure->name . ' (pino elliotis)',
                'product_type_id' => 16,
                'phase_id' => 4,
                'm2' => ProductType::find(16)->measure->m2 * ProductType::find(16)->unity->unities,
                'm2_price' => 600,
                'wood_type_id' => 1,
                'real_stock' => 0,
                'minimum_stock' => 300,
                'iva_type_id' => 2,
                'cost' => 220,
                'margin' => ProductType::find(16)->product_name->margin,
                'selling_price' => 220 * ProductType::find(16)->product_name->margin,
            ],

            // PAQUETE GRANDE MACHIMBRADO DE 10
            [
                'name' => ProductType::find(17)->product_name->name . ' ' . ProductType::find(17)->measure->name . ' (pino elliotis)',
                'product_type_id' => 17,
                'phase_id' => 5,
                'm2' => ProductType::find(17)->measure->m2 * ProductType::find(17)->unity->unities,
                'm2_price' => 600,
                'wood_type_id' => 1,
                'is_salable' => true,
                'real_stock' => 0,
                'minimum_stock' => 4,
                'iva_type_id' => 2,
                'cost' => 600 * ProductType::find(17)->measure->m2 * ProductType::find(17)->unity->unities,
                'margin' => ProductType::find(17)->product_name->margin,
                'selling_price' => 600 * ProductType::find(17)->measure->m2 * ProductType::find(17)->unity->unities * ProductType::find(17)->product_name->margin,
            ],

            // PAQUETE GRANDE MACHIMBRADO DE 11
            [
                'name' => ProductType::find(18)->product_name->name . ' ' . ProductType::find(18)->measure->name . ' (pino elliotis)',
                'product_type_id' => 18,
                'phase_id' => 5,
                'm2' => ProductType::find(18)->measure->m2 * ProductType::find(18)->unity->unities,
                'm2_price' => 625,
                'wood_type_id' => 1,
                'is_salable' => true,
                'real_stock' => 0,
                'minimum_stock' => 4,
                'iva_type_id' => 2,
                'cost' => 625 * ProductType::find(18)->measure->m2 * ProductType::find(18)->unity->unities,
                'margin' => ProductType::find(18)->product_name->margin,
                'selling_price' => 625 * ProductType::find(18)->measure->m2 * ProductType::find(18)->unity->unities * ProductType::find(18)->product_name->margin,
            ],

            // PAQUETE GRANDE MACHIMBRADO DE 12
            [
                'name' => ProductType::find(19)->product_name->name . ' ' . ProductType::find(19)->measure->name . ' (pino elliotis)',
                'product_type_id' => 19,
                'phase_id' => 5,
                'm2' => ProductType::find(19)->measure->m2 * ProductType::find(19)->unity->unities,
                'm2_price' => 650,
                'wood_type_id' => 1,
                'is_salable' => true,
                'real_stock' => 0,
                'minimum_stock' => 4,
                'iva_type_id' => 2,
                'cost' => 650 * ProductType::find(19)->measure->m2 * ProductType::find(19)->unity->unities,
                'margin' => ProductType::find(19)->product_name->margin,
                'selling_price' => 650 * ProductType::find(19)->measure->m2 * ProductType::find(19)->unity->unities * ProductType::find(19)->product_name->margin,
            ],

            // PAQUETE GRANDE MACHIMBRADO DE 13
            [
                'name' => ProductType::find(20)->product_name->name . ' ' . ProductType::find(20)->measure->name . ' (pino elliotis)',
                'product_type_id' => 20,
                'phase_id' => 5,
                'm2' => ProductType::find(20)->measure->m2 * ProductType::find(20)->unity->unities,
                'm2_price' => 675,
                'wood_type_id' => 1,
                'is_salable' => true,
                'real_stock' => 0,
                'minimum_stock' => 4,
                'iva_type_id' => 2,
                'cost' => 675 * ProductType::find(20)->measure->m2 * ProductType::find(20)->unity->unities,
                'margin' => ProductType::find(20)->product_name->margin,
                'selling_price' => 675 * ProductType::find(20)->measure->m2 * ProductType::find(20)->unity->unities * ProductType::find(20)->product_name->margin,
            ],

        ];

        foreach ($products as $product) {
            \App\Models\Product::create($product);
        }
    }
}
