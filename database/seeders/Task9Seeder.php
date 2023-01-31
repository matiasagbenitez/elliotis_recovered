<?php

namespace Database\Seeders;

use App\Http\Services\M2Service;
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

            $produced_quantity = rand(1, 2);
            $m2 = M2Service::calculateM2($product->id, $produced_quantity);

            $outputSelects[] = [
                'product_id' => $product->id,
                'produced_quantity' => $produced_quantity,
                'm2' => $m2,
            ];
        }

        $inputSelects = [];

        foreach ($outputSelects as $item) {

            $product = Product::find($item['product_id']);
            $size = $product->productType->unity->unities * $item['produced_quantity'];

            $previousProduct = $product->previousProduct;
            $sublots = Sublot::where('product_id', $previousProduct->id)->where('phase_id', 4)->where('area_id', 7)->where('available', true)->get();

            if ($sublots->sum('actual_quantity') < $size) {
                return;
            } else if ($sublots->count() == 0) {
                return;
            }

            // Completar el inputSelects con sublots, hasta que la suma de los consumed_quantity sea igual a $size
            $sum = 0;
            $sublots_ids = $sublots->pluck('id')->toArray();

            while ($sum < $size) {

                $sublot = Sublot::find($sublots_ids[array_rand($sublots_ids)]);

                $sum += $sublot->actual_quantity;
                $consumed_quantity = $sublot->actual_quantity;
                $m2 = M2Service::calculateM2($sublot->product_id, $consumed_quantity);

                $inputSelects[] = [
                    'sublot_id' => $sublot->id,
                    'consumed_quantity' => $consumed_quantity,
                    'm2' => $m2,
                ];

                $sublot->update([
                    'actual_quantity' => 0,
                    'available' => 0,
                    'actual_m2' => 0,
                ]);

                // Eliminar el sublot de la lista de sublots
                $sublots_ids = array_diff($sublots_ids, [$sublot->id]);
            }

            // Si la suma es mayor a $size, se resta la diferencia al ultimo consumed_quantity
            if ($sum > $size) {
                $difference = $sum - $size;
                $difference_m2 = $difference * $sublot->product->m2;

                $inputSelects[count($inputSelects) - 1]['consumed_quantity'] -= $difference;
                $inputSelects[count($inputSelects) - 1]['m2'] -= $difference_m2;

                $s = Sublot::find($inputSelects[count($inputSelects) - 1]['sublot_id']);
                $s->update([
                    'actual_quantity' => $sublot->actual_quantity + $difference,
                    'available' => $sublot->actual_quantity + $difference > 0 ? 1 : 0,
                    'actual_m2' => $sublot->actual_m2 + $difference_m2,
                ]);
            }
        }

        $start_date = TaskService::getStartDate();
        $end_date = TaskService::getEndDate($start_date);

        $task = Task::create([
            'type_of_task_id' => 9,
            'task_status_id' => 1,
            'started_at' => $start_date,
            'started_by' => 1,
        ]);

        $task->inputSublotsDetails()->sync($inputSelects);

        foreach ($inputSelects as $item) {

            $product_id = Sublot::find($item['sublot_id'])->product_id;
            $m2 = M2Service::calculateM2($product_id, $item['consumed_quantity']);

            $sublot = Sublot::find($item['sublot_id']);
            // $sublot->update([
            //     'actual_quantity' => $sublot->actual_quantity - $item['consumed_quantity'],
            //     'available' => $sublot->actual_quantity - $item['consumed_quantity'] > 0 ? 1 : 0,
            //     'actual_m2' => $sublot->actual_m2 - $m2,
            // ]);

            $sublot->product->update([
                'real_stock' => $sublot->product->real_stock - $item['consumed_quantity']
            ]);
        }

        $lot = Lot::create([
            'task_id' => $task->id,
            'code' => 'L' . TaskService::getLotCode($task),
            'created_at' => $end_date,
            'updated_at' => $end_date,
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
                'initial_m2' => $item['m2'],
                'actual_m2' => $item['m2'],
            ]);

            $aux[] = [
                'sublot_id' => $sublot->id,
                'produced_quantity' => $sublot->initial_quantity,
                'm2' => $sublot->initial_m2,
            ];

            $sublot->product->update([
                'real_stock' => $sublot->product->real_stock + $sublot->initial_quantity
            ]);
        }

        $task->outputSublotsDetails()->sync($aux);

        // Actualizamos la tarea
        $task->update([
            'task_status_id' => 2,
            'finished_at' => $end_date,
            'finished_by' => 1,
        ]);
    }
}
