<?php

namespace App\Http\Livewire\TypesOfTasks;

use App\Models\Area;
use App\Models\Phase;
use Livewire\Component;
use App\Models\TypeOfTask;

class CreateTypeOfTask extends Component
{
    public $isOpen = 0;

    public $areas = [], $phases = [];

    public $createForm = [
        'type' => '',
        'name' => '',
        'description' => '',
        'initial_task' => 0,
        'movement' => '',
        'origin_area_id' => '',
        'destination_area_id' => '',
        'transformation' => '',
        'initial_phase_id' => '',
        'final_phase_id' => '',
    ];

    protected $rules = [
        'createForm.name' => 'required|string|unique:type_of_tasks,name',
        'createForm.initial_task' => 'required|boolean',
        'createForm.origin_area_id' => 'required|integer',
        'createForm.destination_area_id' => 'required|integer',
        'createForm.initial_phase_id' => 'required|integer',
        'createForm.final_phase_id' => 'required|integer',
    ];

    protected $validationAttributes = [
        'createForm.name' => 'name',
        'createForm.description' => 'description',
        'createForm.initial_task' => 'initial task',
        'createForm.origin_area_id' => 'origin area',
        'createForm.destination_area_id' => 'destination area',
        'createForm.initial_phase_id' => 'initial phase',
        'createForm.final_phase_id' => 'final phase',
    ];

    public function mount()
    {
        $this->areas = Area::all();
        $this->phases = Phase::all();
    }

    public function createTypeOfTask()
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
        $this->reset('createForm');
        $this->resetErrorBag();
    }

    public function save()
    {
        try {
            $this->createForm['origin_area_id'] == $this->createForm['destination_area_id'] ? $this->createForm['movement'] = false : $this->createForm['movement'] = true;
            $this->createForm['initial_phase_id'] == $this->createForm['final_phase_id'] ? $this->createForm['transformation'] = false : $this->createForm['transformation'] = true;

            // dd($this->createForm);
            TypeOfTask::create($this->createForm);

            $this->reset('createForm');
            $this->closeModal();
            $this->emit('success', '??El tipo de tarea se ha creado con ??xito!');
            $this->emit('render');
        } catch (\Throwable $th) {
            // dd($th);
            $this->emit('error', '??No es posible crear el tipo de tarea! Verifica los campos y recuerda que no se pueden repetir la combinaci??n de ??reas y fases iniciales y finales. Adem??s, solo puede haber una tarea inicial.');
        }
    }

    public function render()
    {
        return view('livewire.types-of-tasks.create-type-of-task');
    }
}
