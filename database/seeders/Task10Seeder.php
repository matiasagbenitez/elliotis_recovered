<?php

namespace Database\Seeders;

use App\Models\Lot;
use App\Models\Task;
use App\Models\User;
use App\Models\Sublot;
use Illuminate\Database\Seeder;
use App\Http\Services\M2Service;
use App\Http\Services\TaskService;
use Illuminate\Support\Facades\Date;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class Task10Seeder extends Seeder
{
    public function run()
    {
        $sublots = Sublot::where('phase_id', 5)->where('area_id', 7)->where('available', true)->get();

        $cant = rand(2, 4);
        if ($sublots->count() < $cant) {
            return;
        }
        $sublots = $sublots->random($cant);

        $start_date = TaskService::getStartDate();
        $end_date = TaskService::getEndDate($start_date);

        $user_id = User::whereHas('roles', function ($query) {
            $query->where('name', 'empleado');
        })->get()->random()->id;

        $task = Task::create([
            'type_of_task_id' => 10,
            'task_status_id' => 1,
            'started_at' => $start_date,
            'started_by' => $user_id
        ]);

        foreach ($sublots as $sublot) {

            $consumed_quantity = $sublot->actual_quantity;
            $m2 = M2Service::calculateM2($sublot->product_id, $consumed_quantity);

            $inputSelects[] = [
                'sublot_id' => $sublot->id,
                'consumed_quantity' => $consumed_quantity,
                'm2' => $m2,
            ];
        }

        $task->inputSublotsDetails()->sync($inputSelects);

        $lot = Lot::create([
            'task_id' => $task->id,
            'code' => 'L' . TaskService::getLotCode($task),
            'created_at' => $end_date,
            'updated_at' => $end_date,
        ]);

        foreach ($inputSelects as $item) {

            $product_id = Sublot::find($item['sublot_id'])->product_id;
            $m2 = M2Service::calculateM2($product_id, $item['consumed_quantity']);

            $input_sublot = Sublot::find($item['sublot_id']);
            $input_sublot->update([
                'actual_quantity' => $input_sublot->actual_quantity - $item['consumed_quantity'],
                'available' => $input_sublot->actual_quantity - $item['consumed_quantity'] > 0 ? 1 : 0,
                'actual_m2' => $input_sublot->actual_m2 - $m2,
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
                'initial_m2' => $m2,
                'actual_m2' => $m2,
                'created_at' => $end_date,
                'updated_at' => $end_date,
            ]);

            $aux[] = [
                'sublot_id' => $sublot->id,
                'produced_quantity' => $sublot->initial_quantity,
                'm2' => $sublot->initial_m2,
            ];

        }

        $task->outputSublotsDetails()->sync($aux);

        // Actualizamos la tarea
        $task->update([
            'task_status_id' => 2,
            'finished_at' => $end_date,
            'finished_by' => $user_id
        ]);
    }
}
