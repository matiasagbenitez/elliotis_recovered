<?php

namespace App\Http\Livewire\TaskStatuses;

use Livewire\Component;
use App\Models\TaskStatus;
use Livewire\WithPagination;

class IndexTaskStatuses extends Component
{
    use WithPagination;

    public $search;
    protected $listeners = ['refresh' => 'render', 'delete'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function delete(TaskStatus $task_status)
    {
        try {
            $task_status->delete();
            $this->emit('success', '¡El estado de la tarea se ha eliminado con éxito!');
            $this->emit('refresh');
        } catch (\Exception $e) {
            $this->emit('error', '¡El estado de la tarea no se ha podido eliminar!');
        }
    }

    public function render()
    {
        $task_statuses = TaskStatus::where('name', 'like', '%' . $this->search . '%')
            ->orderBy('name')
            ->paginate(10);
        return view('livewire.task-statuses.index-task-statuses', compact('task_statuses'));
    }
}
