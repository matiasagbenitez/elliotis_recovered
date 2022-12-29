<?php

namespace App\Http\Livewire\Tasks;

use App\Models\User;
use Livewire\Component;
use App\Models\TaskType;
use App\Models\TypeOfTask;
use Illuminate\Support\Facades\Date;

class IndexTasks extends Component
{
    public $tasksTypes, $stats;
    public $filter = 'all';

    public function mount()
    {
        $this->tasksTypes = TypeOfTask::all();
        $this->stats = $this->getStats();
    }

    public function updatedFilter($value)
    {
        switch ($value) {
            case 'all':
                $this->tasksTypes = TypeOfTask::all();
                break;

            case 'production':
                $this->tasksTypes = TypeOfTask::where('transformation', true)->get();
                break;

            case 'movement':
                $this->tasksTypes = TypeOfTask::where('movement', true)->get();
                break;

            default:
                # code...
                break;
        }

        $this->stats = $this->getStats();
    }

    public function getStats()
    {
        $stats = [];

        foreach ($this->tasksTypes as $taskType) {

            $running_task = $taskType->tasks()->where('task_status_id', 1)->first();
            $running_task ? $task = $running_task : $task = $taskType->tasks()->latest()->first();

            $stats[] = [
                'id' => $taskType->id,
                'task_id' => $running_task ? $task->id : null,
                'name' => $taskType->name,
                'icon' => $taskType->icon,
                'running_task' => $task ? ($task->task_status_id == 1 ? true : false) : null,
                'user' =>
                    $task ?
                    (
                        $task->task_status_id == 1
                        ?
                        User::find($task->started_by)->name
                        :
                        User::find($task->finished_by)->name
                    ) : null,
                'date' =>
                $task ?
                (
                    $task->task_status_id == 1
                    ?
                    Date::parse($task->started_at)->format('d/m/Y H:i')
                    :
                    Date::parse($task->finished_at)->format('d/m/Y H:i')
                ) : null,
            ];
        }

        return $stats;
    }

    public function render()
    {
        return view('livewire.tasks.index-tasks');
    }
}
