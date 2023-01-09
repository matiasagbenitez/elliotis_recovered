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
                'description' => 'Movimiento de rollos desde playa de rollos a la línea de corte',
                'initial_task' => true,
                'movement' => true,
                'origin_area_id' => 1,
                'destination_area_id' => 2,
                'transformation' => false,
                'initial_phase_id' => 1,
                'final_phase_id' => 1,
                'icon' => '/img/natural-resources.png'
            ],
            [
                'type' => 'Producción',
                'name' => 'Corte de rollos',
                'description' => 'Corte de rollos en fajas',
                'initial_task' => false,
                'movement' => false,
                'origin_area_id' => 2,
                'destination_area_id' => 2,
                'transformation' => true,
                'initial_phase_id' => 1,
                'final_phase_id' => 2,
                'icon' => '/img/cortadora.png',
            ],
            [
                'type' => 'Movimiento',
                'name' => 'Movimiento para secado',
                'description' => 'Movimiento de fajas desde la línea de corte al sector de secado',
                'initial_task' => false,
                'movement' => true,
                'origin_area_id' => 2,
                'destination_area_id' => 3,
                'transformation' => false,
                'initial_phase_id' => 2,
                'final_phase_id' => 2,
                'icon' => '/img/loader.png',
            ],
            [
                'type' => 'Movimiento/Producción',
                'name' => 'Movimiento a depósito de fajas secas',
                'description' => 'Movimiento de fajas secas desde el sector de secado al depósito',
                'initial_task' => false,
                'movement' => true,
                'origin_area_id' => 3,
                'destination_area_id' => 4,
                'transformation' => true,
                'initial_phase_id' => 2,
                'final_phase_id' => 3,
                'icon' => '/img/loader.png',
            ],
            [
                'type' => 'Movimiento',
                'name' => 'Movimiento para machimbrado',
                'description' => 'Movimiento de fajas desde el depósito a la machimbradora',
                'initial_task' => false,
                'movement' => true,
                'origin_area_id' => 4,
                'destination_area_id' => 5,
                'transformation' => false,
                'initial_phase_id' => 3,
                'final_phase_id' => 3,
                'icon' => '/img/forklift.png',
            ],
            [
                'type' => 'Producción',
                'name' => 'Machimbrado',
                'description' => 'Machimbrado de fajas secas para la obtención de machimbres',
                'initial_task' => false,
                'movement' => false,
                'origin_area_id' => 5,
                'destination_area_id' => 5,
                'transformation' => true,
                'initial_phase_id' => 3,
                'final_phase_id' => 4,
                'icon' => '/img/machimbradora.png',
            ],
            [
                'type' => 'Movimiento',
                'name' => 'Movimiento a depósito de fajas machimbradas',
                'description' => 'Movimiento de fajas machimbradas al depósito de fajas machimbradas',
                'initial_task' => false,
                'movement' => true,
                'origin_area_id' => 5,
                'destination_area_id' => 6,
                'transformation' => false,
                'initial_phase_id' => 4,
                'final_phase_id' => 4,
                'icon' => '/img/forklift.png',
            ],
            [
                'type' => 'Movimiento',
                'name' => 'Movimiento para empaquetado',
                'description' => 'Movimiento de fajas machimbradas desde el depósito a la empaquetadora',
                'initial_task' => false,
                'movement' => true,
                'origin_area_id' => 6,
                'destination_area_id' => 7,
                'transformation' => false,
                'initial_phase_id' => 4,
                'final_phase_id' => 4,
                'icon' => '/img/forklift.png',
            ],
            [
                'type' => 'Producción',
                'name' => 'Empaquetado',
                'description' => 'Empaquetado de fajas machimbradas en paquetes',
                'initial_task' => false,
                'final_task' => true,
                'movement' => false,
                'origin_area_id' => 7,
                'destination_area_id' => 7,
                'transformation' => true,
                'initial_phase_id' => 4,
                'final_phase_id' => 5,
                'icon' => '/img/empaquetadora.png',
            ],
            [
                'type' => 'Movimiento',
                'name' => 'Movimiento a depósito de paquetes',
                'description' => 'Movimiento de paquetes desde la empaquetadora al depósito de paquetes',
                'initial_task' => false,
                'movement' => true,
                'origin_area_id' => 7,
                'destination_area_id' => 8,
                'transformation' => false,
                'initial_phase_id' => 5,
                'final_phase_id' => 5,
                'icon' => '/img/forklift.png',
            ]
        ];

        foreach ($types_of_tasks as $type_of_task) {
            \App\Models\TypeOfTask::create($type_of_task);
        }

    }
}
