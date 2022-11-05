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
        Country::factory(3)->create()->each(function (Country $country) {
            Province::factory(4)->create([
                'country_id' => $country->id
            ])->each(function (Province $province) {
                Locality::factory(5)->create([
                    'province_id' => $province->id
                ]);
            });
        });
    }
}
