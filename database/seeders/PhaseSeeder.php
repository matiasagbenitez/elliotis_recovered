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
                'name' => 'Rollos',
                'slug' => 'rollos',
                'prefix' => 'RO'
            ],
            [
                'name' => 'Fajas húmedas',
                'slug' => 'fajas-humedas',
                'prefix' => 'FH'
            ],
            [
                'name' => 'Fajas secas',
                'slug' => 'fajas-secas',
                'prefix' => 'FS'
            ],
            [
                'name' => 'Fajas machimbradas',
                'slug' => 'fajas-machimbradas',
                'prefix' => 'FM'
            ],
            [
                'name' => 'Paquetes listos',
                'slug' => 'paquetes-listos',
                'prefix' => 'PA'
            ]
        ];

        foreach ($phases as $phase) {
            Phase::create($phase);
        }
    }
}
