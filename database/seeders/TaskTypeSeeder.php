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
            ],
            [
                'name' => 'Machimbrado',
                'area_id' => 4,
                'initial_phase_id' => 3,
                'final_phase_id' => 4,
            ],
            [
                'name' => 'Empaquetado',
                'area_id' => 6,
                'initial_phase_id' => 4,
                'final_phase_id' => 5,
                'final_task' => true
            ]
        ];

        foreach ($tasks as $task) {
            TaskType::create($task);
        }
    }
}
