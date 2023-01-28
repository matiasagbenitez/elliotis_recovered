<?php

namespace Database\Seeders;

use App\Models\Lot;
use App\Models\Task;
use App\Models\Sublot;
use App\Models\Product;
use Illuminate\Database\Seeder;
use App\Http\Services\TaskService;
use Illuminate\Support\Facades\Date;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class Task9Seeder extends Seeder
{
    public function run()
    {
        $sublots = Sublot::where('phase_id', 4)->where('area_id', 7)->where('available', true)->get();

        $cant = rand(1, 2);

        if ($sublots->count() < $cant) {
            return;
        }

        $outputSelects = [];

        for ($i = 0; $i < $cant; $i++) {

            $product = Product::where('phase_id', 5)->get()->random();
            if (in_array($product->id, $outputSelects)) {
                $i--;
                continue;
            }

            $outputSelects[] = [
                'product_id' => $product->id,
                'produced_quantity' => rand(1, 2)
            ];
        }

        $inputSelects = [];

        foreach ($outputSelects as $item) {

            $product = Product::find($item['product_id']);
            $size = $product->productType->unity->unities * $item['produced_quantity'];

            $previousProduct = $product->previousProduct;
            $sublots = Sublot::where('product_id', $previousProduct->id)->where('available', true)->get();

            // Completar el inputSelects con sublots, hasta que la suma de los consumed_quantity sea igual a $size
            $sum = 0;

            while ($sum < $size) {
                $sublot = $sublots->random();
                $sum += $sublot->actual_quantity;

                $inputSelects[] = [
                    'sublot_id' => $sublot->id,
                    'consumed_quantity' => $sublot->actual_quantity
                ];
            }

            // Si la suma es mayor a $size, se resta la diferencia al ultimo consumed_quantity
            if ($sum > $size) {
                $difference = $sum - $size;
                $inputSelects[count($inputSelects) - 1]['consumed_quantity'] -= $difference;
            }

        }

        $start_date = TaskService::getStartDate();

        $task = Task::create([
            'type_of_task_id' => 9,
            'task_status_id' => 1,
            'started_at' => $start_date,
            'started_by' => 1,
        ]);

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
        }

        $lot = Lot::create([
            'task_id' => $task->id,
            'code' => 'L' . TaskService::getLotCode($task),
        ]);

        $aux = [];

        foreach ($outputSelects as $item) {
            $sublot = Sublot::create([
                'code' => 'S' .  TaskService::getSublotCode($task->lot),
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
                'finished_at' => TaskService::getEndDate($start_date),
                'finished_by' => 1,
            ]);

    }
}
