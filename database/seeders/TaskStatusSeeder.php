<?php

namespace Database\Seeders;

use App\Models\TaskStatus;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TaskStatusSeeder extends Seeder
{
    public function run()
    {
        $taskStatuses = [
            [
                'name' => 'En proceso',
                'running' => true,
            ],
            [
                'name' => 'Finalizado',
                'finished' => true,
            ],
            [
                'name' => 'Cancelado',
                'canceled' => true,
            ]
            ,
            [
                'name' => 'Pendiente',
                'pending' => true,
            ]
        ];

        foreach ($taskStatuses as $taskStatus) {
            TaskStatus::create($taskStatus);
        }
    }
}
