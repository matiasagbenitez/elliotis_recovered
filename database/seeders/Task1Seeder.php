<?php

namespace Database\Seeders;

use App\Models\Lot;
use App\Models\Task;
use App\Models\Sublot;
use App\Models\TrunkSublot;
use Illuminate\Database\Seeder;
use App\Http\Services\TaskService;
use Illuminate\Support\Facades\Date;
use App\Http\Services\RandomNumberService;

class Task1Seeder extends Seeder
{
    public function run()
    {
        $task = Task::create([
            'type_of_task_id' => 1,
            'task_status_id' => 1,
            'started_at' => Date::now(),
            'started_by' => 1,
        ]);

        $sublots = TrunkSublot::where('area_id', $task->typeOfTask->origin_area_id)->where('available', true)->get();
        $cant = rand(2, 3);
        $sublots = $sublots->random($cant);

        foreach ($sublots as $sublot) {
            $rand = RandomNumberService::highProbability();
            if ($rand == 1) {
                $consumed_quantity = $sublot->actual_quantity;
            } else {
                $rounded_half = round($sublot->actual_quantity / 2);
                $rounded_half = (int) $rounded_half;
                $consumed_quantity = rand($rounded_half, $sublot->actual_quantity);
            }

            $inputSelects[] = [
                'sublot_id' => $sublot->id,
                'consumed_quantity' => $consumed_quantity
            ];
        }

        $task->trunkSublots()->sync($inputSelects);

        $lot = Lot::create([
            'task_id' => $task->id,
            'code' => 'L' . TaskService::getLotCode($task),
        ]);

        $aux = [];

        foreach ($inputSelects as $item) {

            // Actualizamos el sublote de rollos
            $trunk_sublot = TrunkSublot::find($item['sublot_id']);
            $trunk_sublot->update([
                'actual_quantity' => $trunk_sublot->actual_quantity - $item['consumed_quantity'],
                'available' => $trunk_sublot->actual_quantity - $item['consumed_quantity'] > 0 ? 1 : 0
            ]);

            // Creamos el sublote de salida
            $sublot = Sublot::create([
                'code' => 'S' . TaskService::getSublotCode($task->lot),
                'lot_id' => $lot->id,
                'phase_id' => $task->typeOfTask->final_phase_id,
                'product_id' => $trunk_sublot->product_id,
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
            'finished_at' => Date::now(),
            'finished_by' => 1,
        ]);
    }
}
