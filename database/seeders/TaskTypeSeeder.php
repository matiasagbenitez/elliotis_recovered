<?php

namespace Database\Seeders;

use App\Models\TaskType;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TaskTypeSeeder extends Seeder
{
    public function run()
    {
        $tasks = [
            [
                'name' => 'Corte de rollos',
                'area_id' => 1,
                'initial_phase_id' => 1,
                'final_phase_id' => 2,
                'initial_task' => true,
                'icon' => '/img/cortadora.png',
            ],
            [
                'name' => 'Machimbrado',
                'area_id' => 4,
                'initial_phase_id' => 3,
                'final_phase_id' => 4,
                'icon' => '/img/machimbradora.png',
            ],
            [
                'name' => 'Empaquetado',
                'area_id' => 6,
                'initial_phase_id' => 4,
                'final_phase_id' => 5,
                'final_task' => true,
                'icon' => '/img/empaquetadora.png',
            ]
        ];

        foreach ($tasks as $task) {
            TaskType::create($task);
        }
    }
}
