<?php

namespace App\Http\Livewire\Tasks;

use App\Models\Task;
use App\Models\User;
use App\Models\Sublot;
use Livewire\Component;
use App\Models\TaskStatus;
use App\Models\TypeOfTask;
use Illuminate\Support\Str;
use Livewire\WithPagination;
use App\Models\InputTaskDetail;
use App\Http\Services\TaskService;
use App\Models\InitialTaskDetail;
use Illuminate\Support\Facades\Date;

class ManageTasks extends Component
{

    use WithPagination;

    public $task_type_name, $type_of_task_id, $running_task, $tasks, $employees, $statuses;
    protected $listeners = ['startNewTask', 'finishTask', 'disable'];

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
            $this->emit('success', '¡Tarea de tipo: ' . $this->task_type_name . ' iniciada correctamente!');
        } catch (\Throwable $th) {
            dd($th);
            $this->emit('error', '¡Error al iniciar la tarea de tipo: ' . $this->task_type_name . '!');
        }
        $this->render();
    }

    public function finishTask($id)
    {
        try {
            return redirect()->route('admin.tasks.register', ['task_type' => $this->type_of_task_id, 'task' => $id]);
        } catch (\Throwable $th) {
            dd($th);
            $this->emit('error', '¡Error al finalizar la tarea de tipo ' . Str::lower($this->task_type_name) . '!');
        }
        $this->render();
    }

    public function showFinishedTask($id)
    {
        try {
            return redirect()->route('admin.tasks.show', ['task' => $id]);
        } catch (\Throwable $th) {
            dd($th);
            $this->emit('error', '¡Error al mostrar la tarea de tipo: ' . $this->task_type_name . '!');
        }
        $this->render();
    }

    public function disable($id, $reason)
    {
        try {
            $task = Task::find($id);
            $inputSublots = InputTaskDetail::all();
            $inputTrunkSublots = InitialTaskDetail::all();

            foreach ($task->outputSublotsDetails as $outputSublot) {
                foreach ($inputSublots as $inputSublot) {
                    if ($outputSublot->id == $inputSublot->sublot_id) {
                        $this->emit('error', '¡No se puede anular esta tarea porque los sublotes producidos ya han sido utilizados en otra!');
                        return;
                    }
                }
            }

            // Inhabilitar sublotes producidos
            foreach ($task->outputSublotsDetails as $outputSublot) {
                $outputSublot->update([
                    'available' => false,
                ]);
            }

            if ($task->typeOfTask->initial_task) {
                foreach ($task->trunkSublots as $trunkSublot) {
                    $trunkSublot->update([
                        'available' => true,
                        'actual_quantity' => $trunkSublot->actual_quantity + $trunkSublot->pivot->consumed_quantity,
                    ]);
                }
            } else {
                // Reestablecer sublotes utilizados
                foreach ($task->inputSublotsDetails as $inputSublot) {
                    $inputSublot->update([
                        'available' => true,
                        'actual_quantity' => $inputSublot->actual_quantity + $inputSublot->pivot->consumed_quantity,
                    ]);
                }
            }




            $task->update([
                'task_status_id' => 3,
                'cancelled' => true,
                'cancelled_at' => Date::now(),
                'cancelled_by' => auth()->user()->id,
                'cancelled_reason' => $reason,
            ]);

            $this->emit('success', '¡Tarea anulada correctamente! Sublotes de entrada reestablecidos y sublotes de salida inhabilitados.');
            $this->updatedFilters();

        } catch (\Throwable $th) {
            dd($th);
            $this->emit('error', '¡Error al deshabilitar la tarea de tipo: ' . $this->task_type_name . '!');
        }
    }

    public function render()
    {
        return view('livewire.tasks.manage-tasks');
    }
}
