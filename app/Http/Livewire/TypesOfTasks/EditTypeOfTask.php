<?php

namespace App\Http\Livewire\TypesOfTasks;

use App\Models\Area;
use App\Models\Phase;
use App\Models\TypeOfTask;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class EditTypeOfTask extends Component
{
    use WithFileUploads;

    public $isOpen = 0;

    public $typeOfTask, $typeOfTask_id;
    public $areas = [], $phases = [];

    public $newIcon;

    public $editForm = [
        'type' => '',
        'name' => '',
        'description' => '',
        'initial_task' => '',
        'movement' => '',
        'origin_area_id' => '',
        'destination_area_id' => '',
        'transformation' => '',
        'initial_phase_id' => '',
        'final_phase_id' => '',
        'icon' => '',
    ];

    protected $validationAttributes = [
        'editForm.name' => 'name',
        'editForm.initial_task' => 'initial task',
        'editForm.description' => 'description',
        'editForm.origin_area_id' => 'origin area',
        'editForm.destination_area_id' => 'destination area',
        'editForm.initial_phase_id' => 'initial phase',
        'editForm.final_phase_id' => 'final phase',
        'editForm.icon' => 'icon',
    ];

    public function mount(TypeOfTask $type_of_task)
    {
        $this->typeOfTask = $type_of_task;
        $this->typeOfTask_id = $type_of_task->id;
    }

    public function editTypeOfTask()
    {
        $this->openModal();

        $this->areas = Area::all();
        $this->phases = Phase::all();

        $this->editForm = [
            'type' => $this->typeOfTask->type,
            'name' => $this->typeOfTask->name,
            'description' => $this->typeOfTask->description,
            'initial_task' => $this->typeOfTask->initial_task,
            'movement' => $this->typeOfTask->movement,
            'origin_area_id' => $this->typeOfTask->origin_area_id,
            'destination_area_id' => $this->typeOfTask->destination_area_id,
            'transformation' => $this->typeOfTask->transformation,
            'initial_phase_id' => $this->typeOfTask->initial_phase_id,
            'final_phase_id' => $this->typeOfTask->final_phase_id,
            'icon' => $this->typeOfTask->icon,
        ];
    }

    public function openModal()
    {
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    public function resetInputFields()
    {
        $this->reset('editForm');
        $this->resetErrorBag();
    }

    public function update()
    {
        try {
            $this->editForm['origin_area_id'] == $this->editForm['destination_area_id'] ? $this->editForm['movement'] = false : $this->editForm['movement'] = true;
            $this->editForm['initial_phase_id'] == $this->editForm['final_phase_id'] ? $this->editForm['transformation'] = false : $this->editForm['transformation'] = true;

            $this->validate([
                'editForm.name' => 'required',
                'editForm.initial_task' => 'required',
                'editForm.origin_area_id' => 'required',
                'editForm.destination_area_id' => 'required',
                'editForm.initial_phase_id' => 'required',
                'editForm.final_phase_id' => 'required',
            ]);

            if ($this->newIcon) {
                $icon = $this->newIcon->store('public/img');
                $this->editForm['icon'] = str_replace('public/img', '', $icon);

                // Delete old icon
                if ($this->typeOfTask->icon) {
                    $oldIcon = str_replace('/', '', $this->typeOfTask->icon);
                    Storage::delete('public/img/' . $oldIcon);
                }
            } else {
                $this->editForm['icon'] = $this->typeOfTask->icon;
            }

            $typeOfTask = TypeOfTask::find($this->typeOfTask_id);
            $typeOfTask->update($this->editForm);

            $this->reset('editForm');
            $this->closeModal();
            $this->emit('success', '¡El tipo de tarea se ha actualizado con éxito!');
            $this->emit('render');
        } catch (\Throwable $th) {
            // dd($th);
            $this->emit('error', '¡No es posible crear el tipo de tarea! Verifica los campos y recuerda que no se pueden repetir la combinación de áreas y fases iniciales y finales. Además, solo puede haber una tarea inicial.');
        }
    }

    public function render()
    {
        return view('livewire.types-of-tasks.edit-type-of-task');
    }
}
