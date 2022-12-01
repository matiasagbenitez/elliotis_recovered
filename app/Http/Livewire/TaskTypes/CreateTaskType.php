<?php

namespace App\Http\Livewire\TaskTypes;

use App\Models\Area;
use App\Models\Phase;
use Livewire\Component;
use App\Models\TaskType;

class CreateTaskType extends Component
{
    public $isOpen = 0;
    public $intermediate = 'on';

    public $createForm = [
        'name' => '',
        'area_id' => '',
        'initial_phase_id' => '',
        'final_phase_id' => '',
        'initial_task' => 0,
        'final_task' => 0,
    ];

    protected $rules = [
        'createForm.name' => 'required|unique:task_types,name',
        'createForm.area_id' => 'required',
        'createForm.initial_phase_id' => 'required',
        'createForm.final_phase_id' => 'required',
        'createForm.initial_task' => 'required_without:createForm.final_task',
        'createForm.final_task' => 'required_without:createForm.initial_task',
    ];

    protected $validationAttributes = [
        'createForm.name' => 'name',
        'createForm.area_id' => 'area',
        'createForm.initial_phase_id' => 'initial phase',
        'createForm.final_phase_id' => 'final phase',
        'createForm.initial_task' => 'initial task',
        'createForm.final_task' => 'final task',
    ];

    public function createTaskType()
    {
        $this->resetInputFields();
        $this->openModal();
    }

    public function updatedCreateFormInitialTask($value)
    {
        $this->createForm['final_task'] = 0;
    }

    public function updatedCreateFormFinalTask($value)
    {
        $this->createForm['initial_task'] = 0;
    }

    public function updatedIntermediate($value)
    {
        $this->createForm['initial_task'] = '';
        $this->createForm['final_task'] = '';
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
        $this->reset('createForm');
        $this->resetErrorBag();
    }

    public function save()
    {

        try {
            TaskType::create($this->createForm);
        } catch (\Throwable $th) {
            dd($th);
        }
        $this->reset('createForm');
        $this->closeModal();
        $this->emit('success', '¡El tipo de tarea se ha creado con éxito!');
        $this->emitTo('task-types.index-task-types', 'refresh');
    }

    public function render()
    {
        $areas = Area::all();
        $initial_phases = Phase::all();

        // $final_phases = phases that are not in createForm.initial_phase_id
        $final_phases = Phase::where('id', '!=', $this->createForm['initial_phase_id'])->get();

        return view('livewire.task-types.create-task-type', compact('areas', 'initial_phases', 'final_phases'));
    }
}
