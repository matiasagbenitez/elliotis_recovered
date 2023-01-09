<?php

namespace Database\Seeders;

use App\Models\Lot;
use App\Models\Task;
use App\Models\Sublot;
use App\Models\Product;
use Illuminate\Database\Seeder;
use App\Http\Services\TaskService;
use Illuminate\Support\Facades\Date;
use App\Http\Services\RandomNumberService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class Task4Seeder extends Seeder
{
    public function run()
    {
        $task = Task::create([
            'type_of_task_id' => 4,
            'task_status_id' => 1,
            'started_at' => Date::now(),
            'started_by' => 1,
        ]);

        $sublots = Sublot::where('phase_id', $task->typeOfTask->initial_phase_id)->where('area_id', $task->typeOfTask->origin_area_id)->where('available', true)->get();
        $cant = rand(1, 2);
        $sublots = $sublots->random($cant);

        foreach ($sublots as $sublot) {
            $rand = RandomNumberService::highProbability();
            if ($rand == 1) {
                $consumed_quantity = $sublot->actual_quantity;
            } else {
                $rounded_half = round($sublot->actual_quantity / 2);
                // rounded_half to int
                $rounded_half = (int) $rounded_half;
                $consumed_quantity = rand($rounded_half, $sublot->actual_quantity);
            }

            $inputSelects[] = [
                'sublot_id' => $sublot->id,
                'consumed_quantity' => $consumed_quantity
            ];
        }

        $task->inputSublotsDetails()->sync($inputSelects);

        $outputProducts = [];

        // Actualizamos los sublotes de entrada
        foreach ($inputSelects as $item) {
            $input_sublot = Sublot::find($item['sublot_id']);
            $input_sublot->update([
                'actual_quantity' => $input_sublot->actual_quantity - $item['consumed_quantity'],
                'available' => $input_sublot->actual_quantity - $item['consumed_quantity'] > 0 ? 1 : 0
            ]);

            $input_sublot->product->update([
                'real_stock' => $input_sublot->product->real_stock - $item['consumed_quantity']
            ]);

            // Get first following product
            $followingProduct = Product::find($input_sublot->product_id)->followingProducts()->first();

            $outputProducts[] = [
                'product_id' => $followingProduct->id,
                'produced_quantity' => $item['consumed_quantity'],
            ];
        }

        $lot = Lot::create([
            'task_id' => $task->id,
            'code' => 'L' . TaskService::getLotCode($task),
        ]);

        $aux = [];

        // Creamos los sublotes de salida
        foreach ($outputProducts as $item) {
            $sublot = Sublot::create([
                'code' => 'S' . TaskService::getSublotCode($task->lot),
                'lot_id' => $lot->id,
                'phase_id' => $task->typeOfTask->final_phase_id,
                'product_id' => $item['product_id'],
                'area_id' => $task->typeOfTask->destination_area_id,
                'initial_quantity' => $item['produced_quantity'],
                'actual_quantity' => $item['produced_quantity'],
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
            'finished_at' => now(),
            'finished_by' => 1,
        ]);
    }
}
