<?php

namespace App\Http\Services;

use App\Models\Task;
use App\Models\User;
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
            $allTasks = Task::filter($filters)->where('type_of_task_id', $type_of_task_id)->orderBy('updated_at', 'desc')->paginate(10);
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

}
