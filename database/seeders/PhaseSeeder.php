<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PhaseSeeder extends Seeder
{
    public function run()
    {
        $phases = [
            [
                'name' => 'Rollo para cortar',
            ],
            [
                'name' => 'Faja húmeda para secar',
            ],
            [
                'name' => 'Faja seca para machimbrar',
            ],
            [
                'name' => 'Faja machimbrada para empaquetar',
            ],
            [
                'name' => 'Paquete listo para venta',
            ]
        ];

        foreach ($phases as $phase) {
            \App\Models\Phase::create($phase);
        }
    }
}
