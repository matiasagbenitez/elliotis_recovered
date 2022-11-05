<?php

namespace Database\Seeders;

use App\Models\TaskStatus;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TaskStatusSeeder extends Seeder
{
    public function run()
    {
        $task_statuses = [
            [
                'name' => 'Pendiente',
            ],
            [
                'name' => 'En proceso',
            ],
            [
                'name' => 'Finalizado',
            ],
        ];

        foreach ($task_statuses as $task_status) {
            TaskStatus::create($task_status);
        }
    }
}
