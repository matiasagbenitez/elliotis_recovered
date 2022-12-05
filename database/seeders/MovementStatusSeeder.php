<?php

namespace Database\Seeders;

use App\Models\MovementStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MovementStatusSeeder extends Seeder
{
    public function run()
    {
        $movements_statuses = [
            [
                'name' => 'Pendiente',
            ],
            [
                'name' => 'En proceso',
            ],
            [
                'name' => 'Finalizado',
                'is_finished' => true,
            ],
            [
                'name' => 'Cancelado',
                'is_finished' => true,
            ]
        ];

        foreach ($movements_statuses as $movement_status) {
            MovementStatus::create($movement_status);
        }
    }
}
