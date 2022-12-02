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

    protected $listeners = ['startNewTask', 'finishTask', 'alert' => 'showAlert'];

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
        $this->running_task = $this->getRunningTask();
        $this->employees = User::all();
        $this->statuses = TaskStatus::all();
        $this->tasks = $this->getTasks();
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
        if ($this->running_task) {
            $allTasks = Task::filter($this->filters)->where('task_type_id', $this->task_type_id)->latest()->paginate(10);
        } else {
            $allTasks = Task::filter($this->filters)->where('task_type_id', $this->task_type_id)->orderBy('task_status_id', 'asc')->orderBy('updated_at', 'desc')->paginate(10);
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

    public function getRunningTask()
    {
        $running_task = Task::where('task_type_id', $this->task_type_id)->where('task_status_id', 2)->first();
        return $running_task;
    }

    public function startNewTask()
    {
        try {
            $createForm = [
                'task_type_id' => $this->task_type_id,
                'task_status_id' => 2,
                'started_at' => Date::now(),
                'started_by' => auth()->user()->id,
            ];

            Task::create($createForm);
            $this->running_task = $this->getRunningTask();
            $this->tasks = $this->getTasks();
            $this->emit('success', '¡Tarea de tipo: '. $this->task_type_name .' iniciada correctamente!');
        } catch (\Throwable $th) {
            $this->emit('error', '¡Error al iniciar la tarea de tipo: '. $this->task_type_name .'!');
        }
        $this->render();
    }

    public function finishTask($id)
    {
        try {
            return redirect()->route('admin.tasks.register', ['task' => $id]);
        } catch (\Throwable $th) {
            $this->emit('error', '¡Error al finalizar la tarea de tipo: '. $this->task_type_name .'!');
        }
        $this->render();
    }

    public function showFinishedTask($id)
    {
        try {
            return redirect()->route('admin.tasks.show', ['task' => $id]);
        } catch (\Throwable $th) {
            $this->emit('error', '¡Error al mostrar la tarea de tipo: '. $this->task_type_name .'!');
        }
        $this->render();
    }

    public function render()
    {
        return view('livewire.tasks.tasks-management');
    }
}
