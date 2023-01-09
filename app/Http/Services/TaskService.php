<?php

namespace App\Http\Services;

use App\Models\Lot;
use App\Models\Task;
use App\Models\User;
use App\Models\Sublot;
use App\Models\Product;
use App\Models\TypeOfTask;
use Illuminate\Support\Facades\Date;

class TaskService
{
    public static function getRunningTask(TypeOfTask $task_type)
    {
        $running_task = Task::where('type_of_task_id', $task_type->id)->where('task_status_id', 1)->first();
        return $running_task;
    }

    public static function getTasks($running_task, $filters, $type_of_task_id)
    {
        if ($running_task) {
            $allTasks = Task::filter($filters)->where('type_of_task_id', $type_of_task_id)->latest()->paginate(10);
        } else {
            $allTasks = Task::filter($filters)->where('type_of_task_id', $type_of_task_id)->orderBy('id', 'desc')->paginate(10);
        }

        $tasks = [];

        foreach ($allTasks as $task) {
            $tasks[] = [
                'id' => $task->id,
                'status' => $task->task_status_id,
                'started_at' => Date::parse($task->started_at)->format('d-m-Y H:i'),
                'started_by' => User::find($task->started_by)->name,
                'finished_at' => $task->finished_at ? Date::parse($task->finished_at)->format('d-m-Y H:i') : null,
                'finished_by' => $task->finished_by ? User::find($task->finished_by)->name : null,
            ];
        }

        return $tasks;
    }

    public static function getLotCode()
    {
        $lot = Lot::latest()->first();

        if ($lot) {
            $lastCode = $lot->code;
        } else {
            $lastCode = '0000';
        }

        $lastCode = intval(substr($lastCode, -4));
        $newCode = sprintf("%04d", $lastCode + 1);

        return $newCode;
    }

    public static function getSublotCode(Lot $lot)
    {
        // Si el lote no tiene sublotes, se crea el primer sublote
        if ($lot->sublots->isEmpty()) {
            $lastCode = '00';
        } else {
            $lastCode = $lot->sublots->last()->code;
        }

        $lastCode = intval(substr($lastCode, -4));
        $newCode = sprintf("%02d", $lastCode + 1);

        $aux = rand(1000, 9999);
        return $aux;
        // return $newCode;
    }

    public static function getTotals($inputSelects)
    {
        $totals = [];

        foreach ($inputSelects as $item) {

            $sublot = Sublot::find($item['sublot_id']);
            $followingProduct = Product::find($sublot->product_id)->followingProducts()->first();
            $consumedQuantity = 0;

            foreach ($inputSelects as $item) {
                $sublot = Sublot::find($item['sublot_id']);
                $product = Product::find($sublot->product_id);

                if ($product->followingProducts()->first()->id == $followingProduct->id) {
                    $consumedQuantity += $item['consumed_quantity'];
                }
            }

            $totals[] = [
                "product_id" => $followingProduct->previousProduct->id,
                "quantity" => $consumedQuantity,
                "following_product_id" => $followingProduct->id,
                "size" => $followingProduct->productType->unity->unities,
            ];
        }

        $totals = array_unique($totals, SORT_REGULAR);

        return $totals;
    }
}
