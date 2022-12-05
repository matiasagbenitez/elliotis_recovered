<?php

namespace App\Http\Livewire\TypesOfMovements;

use App\Models\Area;
use Livewire\Component;
use App\Models\TypeOfMovement;

class EditTypeOfMovement extends Component
{
    public $isOpen = 0;
    public $typeOfMovement, $typeOfMovement_id;

    public $editForm = [
        'name' => '',
        'origin_area_id' => '',
        'destination_area_id' => '',
    ];

    protected $validationAttributes = [
        'editForm.name' => 'name',
        'editForm.origin_area_id' => 'origin area',
        'editForm.destination_area_id' => 'destination area',
    ];

    protected $rules = [
        'editForm.name' => 'required',
        // Origin and destination area cannot be the same
        'editForm.origin_area_id' => 'required|different:editForm.destination_area_id',
        'editForm.destination_area_id' => 'required|different:editForm.origin_area_id',
    ];

    public function mount(TypeOfMovement $type_of_movement)
    {
        $this->typeOfMovement = $type_of_movement;
        $this->typeOfMovement_id = $type_of_movement->id;
        $this->editForm['name'] = $type_of_movement->name;
        $this->editForm['origin_area_id'] = $type_of_movement->origin_area_id;
        $this->editForm['destination_area_id'] = $type_of_movement->destination_area_id;
    }

    public function updateTypeOfMovement()
    {
        // dd($this->typeOfMovement);
        $this->resetInputFields();
        $this->openModal();
        $this->editForm['name'] = $this->typeOfMovement->name;
        $this->editForm['origin_area_id'] = $this->typeOfMovement->origin_area_id;
        $this->editForm['destination_area_id'] = $this->typeOfMovement->destination_area_id;
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
        $this->editForm = [
            'name' => '',
            'origin_area_id' => '',
            'destination_area_id' => '',
        ];
        $this->resetErrorBag();
    }

    public function save()
    {
        try {
            $this->validate();
            $this->typeOfMovement->update($this->editForm);
            $this->reset('editForm');
            $this->closeModal();
            $this->emit('success', '¡El tipo de movimiento se ha actualizado con éxito!');
            $this->emitTo('types-of-movements.types-of-movements-index', 'render');
        } catch (\Exception $e) {
            $this->emit('error', '¡Ha ocurrido un error al actualizar el tipo de movimiento!');
        }
    }

    public function render()
    {
        $areas = Area::orderBy('name')->get();
        return view('livewire.types-of-movements.edit-type-of-movement', compact('areas'));
    }
}
