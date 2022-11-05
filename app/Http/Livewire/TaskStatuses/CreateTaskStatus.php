<?php

namespace App\Http\Livewire\TaskStatuses;

use Livewire\Component;
use App\Models\TaskStatus;

class CreateTaskStatus extends Component
{
    public $isOpen = 0;

    public $createForm = [
        'name' => ''
    ];

    protected $rules = [
        'createForm.name' => 'required|unique:task_statuses,name'
    ];

    protected $validationAttributes = [
        'createForm.name' => 'name'
    ];

    public function createTaskStatus()
    {
        $this->resetInputFields();
        $this->openModal();
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
        $this->createForm = [
            'name' => ''
        ];
        $this->resetErrorBag();
    }

    public function save()
    {
        $this->validate();
        TaskStatus::create($this->createForm);
        $this->reset('createForm');
        $this->closeModal();
        $this->emit('success', '¡El estado de la tarea se ha creado con éxito!');
        $this->emitTo('task-statuses.index-task-statuses', 'refresh');
    }

    public function render()
    {
        return view('livewire.task-statuses.create-task-status');
    }
}
