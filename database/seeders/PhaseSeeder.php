<?php

namespace Database\Seeders;

use App\Models\Phase;
use Illuminate\Database\Seeder;

class PhaseSeeder extends Seeder
{
    public function run()
    {
        $phases = [
            [
                'name' => 'Rollo para cortar',
                'slug' => 'rollo-para-cortar',
                'prefix' => 'R'
            ],
            [
                'name' => 'Faja húmeda para secar',
                'slug' => 'faja-humeda-para-secar',
                'prefix' => 'FH'
            ],
            [
                'name' => 'Faja seca para machimbrar',
                'slug' => 'faja-seca-para-machimbrar',
                'prefix' => 'FS'
            ],
            [
                'name' => 'Faja machimbrada para empaquetar',
                'slug' => 'faja-machimbrada-para-empaquetar',
                'prefix' => 'FM'
            ],
            [
                'name' => 'Paquete listo para venta',
                'slug' => 'paquete-listo-para-venta',
                'prefix' => 'P'
            ]
        ];

        foreach ($phases as $phase) {
            Phase::create($phase);
        }
    }
}
