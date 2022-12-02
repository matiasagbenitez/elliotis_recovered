<?php

namespace App\Http\Livewire\Tasks;

use App\Models\Task;
use App\Models\TaskStatus;
use App\Models\User;
use Livewire\Component;
use App\Models\TaskType;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Date;

class TasksManagement extends Component
{
    use WithPagination;

    public $task_type_name, $task_type_id, $running_task;
    public $tasks, $employees, $statuses;

    public $filters = [
        'employee_id' => null,
        'task_status_id' => null,
        'fromDate' => null,
        'toDate' => null,
    ];

    public function mount(TaskType $taskType)
    {
        $this->task_type_name = $taskType->name;
        $this->task_type_id = $taskType->id;
        $this->employees = User::all();
        $this->statuses = TaskStatus::all();
        $this->tasks = $this->getTasks();
        $this->running_task = $this->getRunningTask();
    }

    public function updatedFilters()
    {
        $this->resetPage();
        $this->tasks = $this->getTasks();
    }

    public function resetFilters()
    {
        $this->reset('filters');
        $this->updatedFilters();
    }

    public function getTasks()
    {
        $allTasks = Task::filter($this->filters)->where('task_type_id', $this->task_type_id)->latest()->paginate(10);
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

    public function getRunningTask()
    {
        $running_task = Task::where('task_type_id', $this->task_type_id)->where('task_status_id', 2)->first();
        return $running_task;
    }

    public function render()
    {
        return view('livewire.tasks.tasks-management');
    }
}
