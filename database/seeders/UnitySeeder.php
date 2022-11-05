<?php

namespace Database\Seeders;

use App\Models\Unity;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UnitySeeder extends Seeder
{
    public function run()
    {
        $unities = [
            [
                'name' => 'Unidades',
                'unities' => 1,
            ],
            [
                'name' => 'Paquetes chicos',
                'unities' => 13,
            ],
            [
                'name' => 'Paquetes grandes',
                'unities' => 702,
            ],
        ];

        foreach ($unities as $unity) {
            Unity::create($unity);
        }
    }
}
