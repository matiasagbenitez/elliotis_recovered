<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TypeOfMovementSeeder extends Seeder
{
    public function run()
    {
        $types = [
            [
                'name' => 'Movimiento de línea de corte a área de secado',
                'origin_area_id' => 1,
                'destination_area_id' => 2,
                'icon' => '/img/forklift.png',
            ],
            [
                'name' => 'Movimiento de secado a depósito de fajas secas',
                'origin_area_id' => 2,
                'destination_area_id' => 3,
                'icon' => '/img/forklift.png',
            ]
        ];

        foreach ($types as $type) {
            \App\Models\TypeOfMovement::create($type);
        }
    }
}
