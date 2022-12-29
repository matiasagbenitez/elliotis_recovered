<?php

namespace App\Http\Livewire\Tasks;

use App\Models\Task;
use App\Models\TypeOfTask;
use Livewire\Component;

class RegisterTask extends Component
{
    public $type_of_task, $task;

    public function mount(TypeOfTask $task_type, Task $task)
    {
        if (!$task || $task->started_by != auth()->user()->id || !$task->taskStatus->running) {
            abort(403);
        }

        $this->task = $task;
        $this->type_of_task = $task_type;
    }

    public function render()
    {
        return view('livewire.tasks.register-task');
    }
}
