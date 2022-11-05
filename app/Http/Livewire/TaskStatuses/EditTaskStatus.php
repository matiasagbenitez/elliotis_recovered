<?php

namespace App\Http\Livewire\TaskStatuses;

use Livewire\Component;
use App\Models\TaskStatus;

class EditTaskStatus extends Component
{
    public $isOpen = 0;
    public $task_status, $task_status_id;

    public $editForm = [
        'name' => ''
    ];

    protected $validationAttributes = [
        'editForm.name' => 'name'
    ];

    public function mount(TaskStatus $task_status)
    {
        $this->task_status = $task_status;
        $this->task_status_id = $task_status->id;
        $this->editForm = ['name' => $task_status->name];
    }

    public function editTaskStatus()
    {
        $this->openModal();
        $this->resetInputFields();
        $this->editForm = ['name' => $this->task_status->name];
    }

    public function openModal()
    {
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    private function resetInputFields()
    {
        $this->resetErrorBag();
    }

    public function update()
    {
        $this->validate([
            'editForm.name' => 'required|unique:task_statuses,name,' . $this->task_status_id
        ]);
        $this->task_status->update($this->editForm);
        $this->closeModal();
        $this->emit('success', '¡El estado de la tarea se ha actualizado con éxito!');
        $this->emitTo('task-statuses.index-task-statuses', 'refresh');
    }


    public function render()
    {
        return view('livewire.task-statuses.edit-task-status');
    }
}
