<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsualCoordinatesSeeder extends Seeder
{
    public function run()
    {
        $usual_coordinates = [
            [
                'city' => 'Alba Posse',
                'lat' => '-27.55',
                'lon' => '-54.7',
            ],
            [
                'city' => 'Apóstoles',
                'lat' => '-27.91667',
                'lon' => '-55.76667',
            ],
            [
                'city' => 'Aristóbulo del Valle',
                'lat' => '-27.11667',
                'lon' => '-54.91667',
            ],
            [
                'city' => 'Bernardo de Irigoyen',
                'lat' => '-26.25',
                'lon' => '-53.65',
            ],
            [
                'city' => 'Campo Viera',
                'lat' => '-27.38333',
                'lon' => '-55.03333',
            ],
            [
                'city' => 'Candelaria',
                'lat' => '-27.46667',
                'lon' => '-55.73333',
            ],
            [
                'city' => 'Capiovi',
                'lat' => '-26.93333',
                'lon' => '-55.06667',
            ],
            [
                'city' => 'Caraguatay',
                'lat' => '-26.61667',
                'lon' => '-54.76667',
            ],
            [
                'city' => 'Cerro Azul',
                'lat' => '-27.63333',
                'lon' => '-55.48333',
            ],
            [
                'city' => 'Concepcion de la Sierra',
                'lat' => '-27.98333',
                'lon' => '-55.61667',
            ],
            [
                'city' => 'Dos de Mayo',
                'lat' => '-27.46667',
                'lon' => '-55.01667',
            ],
            [
                'city' => 'El Alcazar',
                'lat' => '-26.73333',
                'lon' => '-54.78333',
            ],
            // Completar
            [
                'city' => 'El Soberbio',
                'lat' => '-27.3',
                'lon' => '-54.21667',
            ],
            [
                'city' => 'Eldorado',
                'lat' => '-26.4',
                'lon' => '-54.63333',
            ],
            [
                'city' => 'Fachinal',
                'lat' => '-27.65',
                'lon' => '-55.7',
            ],
            [
                'city' => 'Garuhape',
                'lat' => '-26.8',
                'lon' => '-54.96667',
            ],
            ['city' => 'Garuhape', 'lat' => '-26.8', 'lon' => '-54.96667'],
            ['city' => 'Garupá', 'lat' => '-27.48333', 'lon' => '-55.83333'],
            ['city' => 'Gobernador Roca', 'lat' => '-27.18333', 'lon' => '-55.46667'],
            ['city' => 'Guaraní', 'lat' => '-27.51667', 'lon' => '-55.16667'],
            ['city' => 'Hipólito Yrigoyen', 'lat' => '-27.1', 'lon' => '-55.28333'],
            ['city' => 'Jardín América', 'lat' => '-27.04346', 'lon' => '-55.22698'],
            ['city' => 'Leandro N Alem', 'lat' => '-27.6', 'lon' => '-55.31667'],
            ['city' => 'Montecarlo', 'lat' => '-26.56667', 'lon' => '-54.78333'],
            ['city' => 'Oberá', 'lat' => '-27.48333', 'lon' => '-55.13333'],
            ['city' => 'Panambí', 'lat' => '-27.71667', 'lon' => '-54.91667'],
            ['city' => 'Posadas', 'lat' => '-27.38333', 'lon' => '-55.88333'],
            ['city' => 'Puerto Iguazú', 'lat' => '-25.59912', 'lon' => '-54.57355'],
            ['city' => 'Puerto Leoni', 'lat' => '-26.98333', 'lon' => '-55.16667'],
            ['city' => 'Puerto Libertad', 'lat' => '-25.91667', 'lon' => '-54.6'],
            ['city' => 'Puerto Piray', 'lat' => '-26.46667', 'lon' => '-54.7'],
            ['city' => 'Puerto Rico', 'lat' => '-26.8', 'lon' => '-55.03333'],
            ['city' => 'Ruiz de Montoya', 'lat' => '-26.98333', 'lon' => '-55.05'],
            ['city' => 'Santo Pipó', 'lat' => '-27.13333', 'lon' => '-55.41667'],
            ['city' => 'San Vicente', 'lat' => '-26.9931', 'lon' => '-54.4869'],
            ['city' => 'Veinticinco de Mayo', 'lat' => '-27.38333', 'lon' => '-54.76667'],
            ['city' => 'Wanda', 'lat' => '-25.96667', 'lon' => '-54.58333']

        ];

        foreach ($usual_coordinates as $usual_coordinate) {
            \App\Models\UsualCoordinate::create($usual_coordinate);
        }
    }
}
