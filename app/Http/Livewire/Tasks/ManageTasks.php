<?php

namespace App\Http\Livewire\Tasks;

use App\Http\Services\TaskService;
use App\Models\Task;
use App\Models\User;
use Livewire\Component;
use App\Models\TaskStatus;
use App\Models\TypeOfTask;
use Illuminate\Support\Str;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Date;

class ManageTasks extends Component
{

    use WithPagination;

    public $task_type_name, $type_of_task_id, $running_task, $tasks, $employees, $statuses;
    protected $listeners = ['startNewTask', 'finishTask'];

    public $filters = [
        'employee_id' => null,
        'task_status_id' => null,
        'fromDate' => null,
        'toDate' => null,
    ];

    public function mount(TypeOfTask $task_type)
    {
        $this->task_type_name = $task_type->name;
        $this->type_of_task_id = $task_type->id;
        $this->running_task = TaskService::getRunningTask($task_type);
        $this->tasks = TaskService::getTasks($this->running_task, $this->filters, $this->type_of_task_id);
        $this->employees = User::all();
        $this->statuses = TaskStatus::all();
    }

    public function updatedFilters()
    {
        $this->resetPage();
        $this->tasks = TaskService::getTasks($this->running_task, $this->filters, $this->type_of_task_id);
    }

    public function resetFilters()
    {
        $this->reset('filters');
        $this->updatedFilters();
    }

    public function startNewTask()
    {
        try {
            $createForm = [
                'type_of_task_id' => $this->type_of_task_id,
                'task_status_id' => 1,
                'started_at' => Date::now(),
                'started_by' => auth()->user()->id,
            ];

            Task::create($createForm);
            $this->running_task = TaskService::getRunningTask(TypeOfTask::find($this->type_of_task_id));
            $this->tasks = TaskService::getTasks($this->running_task, $this->filters, $this->type_of_task_id);
            $this->emit('success', '¡Tarea de tipo: '. $this->task_type_name .' iniciada correctamente!');
        } catch (\Throwable $th) {
            dd($th);
            $this->emit('error', '¡Error al iniciar la tarea de tipo: '. $this->task_type_name .'!');
        }
        $this->render();
    }

    public function finishTask($id)
    {
        try {
            return redirect()->route('admin.tasks.register', ['task' => $id]);
        } catch (\Throwable $th) {
            $this->emit('error', '¡Error al finalizar la tarea de tipo '. Str::lower($this->task_type_name) .'!');
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
        return view('livewire.tasks.manage-tasks');
    }
}
