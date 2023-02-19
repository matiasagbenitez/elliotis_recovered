<?php

namespace Database\Seeders;

use App\Models\WeatherApi;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class WeatherApiSeeder extends Seeder
{
    public function run()
    {
        $conditions = [
            'temp' => 18.5,
            'rain_prob' => 0.5,
            'rain_mm' => 5,
            'humidity' => 0.7,
            'wind_speed' => 1,
            'days_in_row' => 5,
            'max_conditions' => 4,
        ];

        WeatherApi::create($conditions);
    }
}
