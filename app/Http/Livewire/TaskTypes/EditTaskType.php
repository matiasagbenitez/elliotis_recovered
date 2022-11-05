<?php

namespace App\Http\Livewire\TaskTypes;

use Livewire\Component;
use App\Models\TaskType;

class EditTaskType extends Component
{
    public $isOpen = 0;
    public $task_type, $task_type_id;

    public $editForm = ['name' => ''];

    protected $validationAttributes = [
        'editForm.name' => 'name'
    ];

    public function mount(TaskType $task_type)
    {
        $this->task_type = $task_type;
        $this->task_type_id = $task_type->id;
        $this->editForm['name'] = $task_type->name;
    }

    public function editTaskType()
    {
        $this->resetInputFields();
        $this->openModal();
        $this->editForm['name'] = $this->task_type->name;
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
            'editForm.name' => 'required|unique:task_types,name,' . $this->task_type_id
        ]);
        $this->task_type->update($this->editForm);
        $this->reset('editForm');
        $this->closeModal();
        $this->emit('success', '¡El tipo de tarea se ha actualizado con éxito!');
        $this->emitTo('task-types.index-task-types', 'refresh');
    }

    public function render()
    {
        return view('livewire.task-types.edit-task-type');
    }
}
