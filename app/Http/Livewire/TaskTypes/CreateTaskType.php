<?php

namespace App\Http\Livewire\TaskTypes;

use Livewire\Component;
use App\Models\TaskType;

class CreateTaskType extends Component
{
    public $isOpen = 0;

    public $createForm = [
        'name' => ''
    ];

    protected $rules = [
        'createForm.name' => 'required|unique:task_types,name'
    ];

    protected $validationAttributes = [
        'createForm.name' => 'name'
    ];

    public function createTaskType()
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
        TaskType::create($this->createForm);
        $this->reset('createForm');
        $this->closeModal();
        $this->emit('success', '¡El tipo de tarea se ha creado con éxito!');
        $this->emitTo('task-types.index-task-types', 'refresh');
    }

    public function render()
    {
        return view('livewire.task-types.create-task-type');
    }
}
