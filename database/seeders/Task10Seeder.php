<?php

namespace Database\Seeders;

use App\Models\Lot;
use App\Models\Task;
use App\Models\Sublot;
use Illuminate\Database\Seeder;
use App\Http\Services\TaskService;
use Illuminate\Support\Facades\Date;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class Task10Seeder extends Seeder
{
    public function run()
    {
        $sublots = Sublot::where('phase_id', 5)->where('area_id', 7)->where('available', true)->get();
        $cant = rand(2, 3);
        if ($sublots->count() < $cant) {
            return;
        }
        $sublots = $sublots->random($cant);

        $start_date = TaskService::getStartDate();

        $task = Task::create([
            'type_of_task_id' => 10,
            'task_status_id' => 1,
            'started_at' => $start_date,
            'started_by' => 1,
        ]);

        foreach ($sublots as $sublot) {

            $consumed_quantity = $sublot->actual_quantity;

            $inputSelects[] = [
                'sublot_id' => $sublot->id,
                'consumed_quantity' => $consumed_quantity
            ];
        }

        $task->inputSublotsDetails()->sync($inputSelects);

        $lot = Lot::create([
            'task_id' => $task->id,
            'code' => 'L' . TaskService::getLotCode($task),
        ]);

        foreach ($inputSelects as $item) {
            $input_sublot = Sublot::find($item['sublot_id']);
            $input_sublot->update([
                'actual_quantity' => $input_sublot->actual_quantity - $item['consumed_quantity'],
                'available' => $input_sublot->actual_quantity - $item['consumed_quantity'] > 0 ? 1 : 0
            ]);

            // Creamos el sublote de salida
            $sublot = Sublot::create([
                'code' => 'S' . TaskService::getSublotCode($task->lot),
                'lot_id' => $lot->id,
                'phase_id' => $task->typeOfTask->final_phase_id,
                'product_id' => $input_sublot->product_id,
                'area_id' => $task->typeOfTask->destination_area_id,
                'initial_quantity' => $item['consumed_quantity'],
                'actual_quantity' => $item['consumed_quantity'],
            ]);

            $aux[] = [
                'sublot_id' => $sublot->id,
                'produced_quantity' => $sublot->initial_quantity,
            ];

        }

        $task->outputSublotsDetails()->sync($aux);

        // Actualizamos la tarea
        $task->update([
            'task_status_id' => 2,
            'finished_at' => TaskService::getEndDate($start_date),
            'finished_by' => 1,
        ]);
    }
}