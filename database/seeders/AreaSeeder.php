<?php

namespace Database\Seeders;

use App\Models\Area;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AreaSeeder extends Seeder
{
    public function run()
    {
        $areas = [
            'Línea de corte',
            'Área de secado',
            'Depósito de fajas secas',
            'Machimbradora',
            'Depósito de fajas machimbradas',
            'Empaquetadora',
            'Depósito de paquetes machimbrados',
        ];

        foreach ($areas as $area) {
            Area::create(['name' => $area]);
        }
    }
}
