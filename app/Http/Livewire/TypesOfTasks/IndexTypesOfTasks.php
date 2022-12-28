<?php

namespace App\Http\Livewire\TypesOfTasks;

use Livewire\Component;
use App\Models\TypeOfTask;
use Livewire\WithPagination;

class IndexTypesOfTasks extends Component
{
    use WithPagination;
    public $search;
    protected $listeners = ['render', 'delete'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function delete(TypeOfTask $type_of_task)
    {
        try {
            $type_of_task->delete();
            $this->emit('success', '¡Tipo de tarea eliminada correctamente!');
        } catch (\Throwable $th) {
            $this->emit('error', '¡No se pudo eliminar el tipo de tarea!');
        }
    }

    public function render()
    {
        $types_of_tasks = TypeOfTask::where('name', 'LIKE', '%' . $this->search . '%')
            ->paginate(10);
        return view('livewire.types-of-tasks.index-types-of-tasks', compact('types_of_tasks'));
    }
}
