<?php

namespace App\Http\Livewire\TaskTypes;

use Livewire\Component;
use App\Models\TaskType;
use Livewire\WithPagination;

class IndexTaskTypes extends Component
{
    use WithPagination;

    public $search;
    protected $listeners = ['delete', 'refresh' => 'render'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function delete(TaskType $taskType)
    {
        try {
            $taskType->delete();
            $this->emit('refresh');
            $this->emit('success', '¡Tipo de tarea eliminado con éxito!');
        } catch (\Exception $e) {
            $this->emit('error', 'No puedes eliminar este tipo de tarea porque tiene tareas asociadas.');
        }
    }

    public function render()
    {
        $task_types = TaskType::where('name', 'LIKE', "%" . $this->search . "%")->orderBy('updated_at', 'DESC')->paginate(6);
        return view('livewire.task-types.index-task-types', compact('task_types'));
    }
}
