<?php

namespace Database\Seeders;

use App\Http\Services\M2Service;
use App\Models\Lot;
use App\Models\Task;
use App\Models\Sublot;
use Illuminate\Database\Seeder;
use App\Http\Services\TaskService;
use Illuminate\Support\Facades\Date;
use App\Http\Services\RandomNumberService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class Task2Seeder extends Seeder
{
    public function run()
    {

        $sublots = Sublot::where('phase_id', 1)->where('area_id', 2)->where('available', true)->get();
        // $cant = rand(4, 5);
        $cant = rand(2, 3);
        if ($sublots->count() < $cant) {
            return;
        }

        $sublots = $sublots->random($cant);

        $start_date = TaskService::getStartDate();

        $task = Task::create([
            'type_of_task_id' => 2,
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

        foreach ($inputSelects as $item) {

            $sublot = Sublot::find($item['sublot_id']);
            $sublot->update([
                'actual_quantity' => $sublot->actual_quantity - $item['consumed_quantity'],
                'available' => $sublot->actual_quantity - $item['consumed_quantity'] > 0 ? 1 : 0
            ]);

            $sublot->product->update([
                'real_stock' => $sublot->product->real_stock - $item['consumed_quantity']
            ]);

            $outputSelects[] = [
                'product_id' => $sublot->product->followingProducts->last()->id,
                'produced_quantity' => $item['consumed_quantity'] * 9,
            ];
        }

        $lot = Lot::create([
            'task_id' => $task->id,
            'code' => 'L' . TaskService::getLotCode($task),
        ]);

        $aux = [];

        foreach ($outputSelects as $item) {

            $m2 = M2Service::calculateM2($item['product_id'], $item['produced_quantity']);

            $sublot = Sublot::create([
                'code' => 'S' .  TaskService::getSublotCode($task->lot),
                'lot_id' => $lot->id,
                'phase_id' => $task->typeOfTask->final_phase_id,
                'product_id' => $item['product_id'],
                'area_id' => $task->typeOfTask->destination_area_id,
                'initial_quantity' => $item['produced_quantity'],
                'actual_quantity' => $item['produced_quantity'],
                'initial_m2' => $m2,
                'actual_m2' => $m2,
            ]);

            $aux[] = [
                'sublot_id' => $sublot->id,
                'produced_quantity' => $sublot->initial_quantity,
            ];

            $sublot->product->update([
                'real_stock' => $sublot->product->real_stock + $sublot->initial_quantity
            ]);
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
