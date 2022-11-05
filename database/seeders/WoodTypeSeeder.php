<?php

namespace Database\Seeders;

use App\Models\WoodType;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class WoodTypeSeeder extends Seeder
{
    public function run()
    {
        $wood_types = [
            [
                'name' => 'Pino elliotis',
            ],
            [
                'name' => 'Pino paraná',
            ],
            [
                'name' => 'Pino radiata',
            ],
            [
                'name' => 'Eucalipto',
            ],
            [
                'name' => 'Kiri',
            ]
        ];

        foreach ($wood_types as $wood_type) {
            WoodType::create($wood_type);
        }
    }
}
