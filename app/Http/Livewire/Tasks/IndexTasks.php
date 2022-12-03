<?php

namespace App\Http\Livewire\Tasks;

use App\Models\User;
use Livewire\Component;
use App\Models\TaskType;
use Illuminate\Support\Facades\Date;

class IndexTasks extends Component
{
    public $tasksTypes, $stats;

    public function mount()
    {
        $this->tasksTypes = TaskType::all();
        $this->stats = $this->getStats();
    }

    public function getStats()
    {
        $stats = [];

        foreach ($this->tasksTypes as $taskType) {

            $running_task = $taskType->tasks()->where('task_status_id', 2)->first();
            $running_task ? $task = $running_task : $task = $taskType->tasks()->latest()->first();

            $stats[] = [
                'id' => $taskType->id,
                'task_id' => $running_task ? $task->id : null,
                'name' => $taskType->name,
                'icon' => $taskType->icon,
                'running_task' => $task ? ($task->task_status_id == 2 ? true : false) : null,
                'user' =>
                    $task ?
                    (
                        $task->task_status_id == 2
                        ?
                        User::find($task->started_by)->name
                        :
                        User::find($task->finished_by)->name
                    ) : null,
                'date' =>
                $task ?
                (
                    $task->task_status_id == 2
                    ?
                    Date::parse($task->started_at)->format('d/m/Y H:i')
                    :
                    Date::parse($task->finished_at)->format('d/m/Y H:i')
                ) : null,
            ];
        }

        // dd($stats);

        return $stats;
    }

    public function render()
    {
        return view('livewire.tasks.index-tasks');
    }
}
