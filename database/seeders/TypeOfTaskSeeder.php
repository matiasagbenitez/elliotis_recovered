<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TypeOfTaskSeeder extends Seeder
{
    public function run()
    {
        $types_of_tasks = [
            [
                'type' => 'Movimiento (tarea inicial)',
                'name' => 'Movimiento de rollos para corte',
                'initial_task' => true,
                'movement' => true,
                'origin_area_id' => 1,
                'destination_area_id' => 2,
                'transformation' => false,
                'initial_phase_id' => 1,
                'final_phase_id' => 1,
            ],
            [
                'type' => 'Producción',
                'name' => 'Corte de rollos',
                'initial_task' => false,
                'movement' => false,
                'origin_area_id' => 2,
                'destination_area_id' => 2,
                'transformation' => true,
                'initial_phase_id' => 1,
                'final_phase_id' => 2,
            ],
            [
                'type' => 'Movimiento',
                'name' => 'Movimiento para secado',
                'initial_task' => false,
                'movement' => true,
                'origin_area_id' => 2,
                'destination_area_id' => 3,
                'transformation' => false,
                'initial_phase_id' => 2,
                'final_phase_id' => 2,
            ],
            [
                'type' => 'Movimiento/Producción',
                'name' => 'Movimiento a depósito de fajas secas',
                'initial_task' => false,
                'movement' => true,
                'origin_area_id' => 3,
                'destination_area_id' => 4,
                'transformation' => true,
                'initial_phase_id' => 2,
                'final_phase_id' => 3,
            ],
            [
                'type' => 'Movimiento',
                'name' => 'Movimiento para machimbrado',
                'initial_task' => false,
                'movement' => true,
                'origin_area_id' => 4,
                'destination_area_id' => 5,
                'transformation' => false,
                'initial_phase_id' => 3,
                'final_phase_id' => 3,
            ],
            [
                'type' => 'Producción',
                'name' => 'Machimbrado',
                'initial_task' => false,
                'movement' => false,
                'origin_area_id' => 5,
                'destination_area_id' => 5,
                'transformation' => true,
                'initial_phase_id' => 3,
                'final_phase_id' => 4,
            ],
            [
                'type' => 'Movimiento',
                'name' => 'Movimiento a depósito de fajas machimbradas',
                'initial_task' => false,
                'movement' => true,
                'origin_area_id' => 5,
                'destination_area_id' => 6,
                'transformation' => false,
                'initial_phase_id' => 4,
                'final_phase_id' => 4,
            ],
            [
                'type' => 'Movimiento',
                'name' => 'Movimiento para empaquetado',
                'initial_task' => false,
                'movement' => true,
                'origin_area_id' => 6,
                'destination_area_id' => 7,
                'transformation' => false,
                'initial_phase_id' => 4,
                'final_phase_id' => 4,
            ],
            [
                'type' => 'Producción',
                'name' => 'Empaquetado',
                'initial_task' => false,
                'movement' => false,
                'origin_area_id' => 7,
                'destination_area_id' => 7,
                'transformation' => true,
                'initial_phase_id' => 4,
                'final_phase_id' => 5,
            ],
            [
                'type' => 'Movimiento',
                'name' => 'Movimiento a depósito de paquetes',
                'initial_task' => false,
                'movement' => true,
                'origin_area_id' => 7,
                'destination_area_id' => 8,
                'transformation' => false,
                'initial_phase_id' => 5,
                'final_phase_id' => 5,
            ]
        ];

        foreach ($types_of_tasks as $type_of_task) {
            \App\Models\TypeOfTask::create($type_of_task);
        }

    }
}
