<?php

namespace App\Http\Services;

use DateTime;
use DateInterval;
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

    public static function getStartDate()
    {
        $endTime = '17:30:00';
        $nextDayStart = '08:30:00';

        $task = Task::latest()->first();

        if ($task) {
            $lastDate = Date::parse($task->started_at);
        } else {
            $lastDate = Date::create(2023, 01, 02, 8, 30, 0);
        }

        $hours = rand(2, 3);
        $minutes = rand(0, 59);

        $startDate = $lastDate->addHours($hours)->addMinutes($minutes);

        if ($startDate->format('N') >= 6){
            $startDate->modify('next monday ' . $nextDayStart);
        }

        if ($startDate->format('H:i:s') > $endTime) {
            $startDate->modify('+1 day ' . $nextDayStart);
        }

        if (Task::whereDate('created_at', $startDate->format('Y-m-d'))->count() >= 4) {
            $startDate->modify('+1 day ' . $nextDayStart);
        }

        return $startDate;
    }

    public static function getEndDate($start_date)
    {

        $end_date = Date::parse($start_date)->addHours(rand(2, 3))->addMinutes(rand(0, 59));

        return $end_date;

    }
}
