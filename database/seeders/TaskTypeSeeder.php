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
                'name' => 'Corte de rollos'
            ],
            [
                'name' => 'Machimbrado'
            ],
            [
                'name' => 'Empaquetado'
            ]
        ];

        foreach ($tasks as $task) {
            TaskType::create($task);
        }
    }
}
