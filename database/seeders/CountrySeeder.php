<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\Locality;
use App\Models\Province;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    public function run()
    {
        Country::create([
            'name' => 'Argentina',
        ]);

        Province::create([
            'name' => 'Misiones',
            'country_id' => 1,
        ]);

        $localities = [
            'Posadas',
            'Garupá',
            'Santa Ana',
            'Jardín América',
            'Capioví',
            'Puerto Rico',
            'San Vicente',
            'Montecarlo',
            'Eldorado',
            'Puerto Iguazú',
            'Guaraní',
            'Puerto Leoni',
        ];

        foreach ($localities as $locality) {
            Locality::create([
                'name' => $locality,
                'province_id' => 1,
            ]);
        }

        Province::create([
            'name' => 'Buenos Aires',
            'country_id' => 1,
        ]);

        $localities = [
            'La Plata',
            'Zárate',
            'Campana',
            'Escobar',
        ];

        foreach ($localities as $locality) {
            Locality::create([
                'name' => $locality,
                'province_id' => 2,
            ]);
        }
    }
}
