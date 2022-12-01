<?php

namespace App\Http\Livewire\TaskTypes;

use Livewire\Component;
use App\Models\TaskType;

class EditTaskType extends Component
{
    public $isOpen = 0;
    public $intermediate;
    public $task_type, $task_type_id;

    public $editForm = [
        'name' => '',
        'area_id' => '',
        'initial_phase_id' => '',
        'final_phase_id' => '',
        'initial_task' => '',
        'final_task' => ''
    ];

    protected $validationAttributes = [
        'editForm.name' => 'name',
        'editForm.area_id' => 'area',
        'editForm.initial_phase_id' => 'initial phase',
        'editForm.final_phase_id' => 'final phase',
        'editForm.initial_task' => 'initial task',
        'editForm.final_task' => 'final task',
    ];

    public function mount(TaskType $task_type)
    {
        $this->task_type = $task_type;
        $this->task_type_id = $task_type->id;
    }

    public function editTaskType()
    {
        $this->resetInputFields();
        $this->openModal();
        $this->editForm['name'] = $this->task_type->name;
        $this->editForm['area_id'] = $this->task_type->area_id;
        $this->editForm['initial_phase_id'] = $this->task_type->initial_phase_id;
        $this->editForm['final_phase_id'] = $this->task_type->final_phase_id;
        $this->editForm['initial_task'] = $this->task_type->initial_task;
        $this->editForm['final_task'] = $this->task_type->final_task;

        if ($this->task_type->initial_task != 1 && $this->task_type->final_task != 1) {
            $this->intermediate = 'on';
        }
    }

    public function updatedEditFormInitialTask($value)
    {
        $this->editForm['final_task'] = 0;
    }

    public function updatedEditFormFinalTask($value)
    {
        $this->editForm['initial_task'] = 0;
    }

    public function updatedIntermediate($value)
    {
        $this->editForm['initial_task'] = '';
        $this->editForm['final_task'] = '';
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
            'editForm.name' => 'required|unique:task_types,name,' . $this->task_type_id,
            'editForm.area_id' => 'required',
            'editForm.initial_phase_id' => 'required',
            'editForm.final_phase_id' => 'required',
            'editForm.initial_task' => 'required_without:editForm.final_task',
            'editForm.final_task' => 'required_without:editForm.initial_task',
        ]);
        // dd($this->editForm);
        $this->task_type->update($this->editForm);
        $this->reset('editForm');
        $this->closeModal();
        $this->emit('success', '¡El tipo de tarea se ha actualizado con éxito!');
        $this->emitTo('task-types.index-task-types', 'refresh');
    }

    public function render()
    {
        $areas = \App\Models\Area::all();
        $initial_phases = \App\Models\Phase::all();
        $final_phases = \App\Models\Phase::where('id', '!=', $this->editForm['initial_phase_id'])->get();
        return view('livewire.task-types.edit-task-type', compact('areas', 'initial_phases', 'final_phases'));
    }
}
