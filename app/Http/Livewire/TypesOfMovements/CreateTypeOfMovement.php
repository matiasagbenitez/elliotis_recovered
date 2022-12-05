<?php

namespace App\Http\Livewire\TypesOfMovements;

use App\Models\Area;
use Livewire\Component;
use App\Models\TypeOfMovement;

class CreateTypeOfMovement extends Component
{
    public $isOpen = 0;

    public $createForm = [
        'name' => '',
        'origin_area_id' => '',
        'destination_area_id' => '',
    ];

    protected $rules = [
        'createForm.name' => 'required',
        // Origin and destination area cannot be the same
        'createForm.origin_area_id' => 'required|different:createForm.destination_area_id',
        'createForm.destination_area_id' => 'required|different:createForm.origin_area_id',
    ];

    public function createTypeOfMovement()
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
            TypeOfMovement::create($this->createForm);
            $this->reset('createForm');
            $this->closeModal();
            $this->emit('success', '¡El tipo de movimiento se ha creado con éxito!');
            $this->emitTo('types-of-movements.index-types-of-movements', 'render');
        } catch (\Throwable $th) {
            $this->emit('error', '¡No se pudo crear el tipo de movimiento!');
        }
    }

    public function render()
    {
        $areas = Area::orderBy('name')->get();
        return view('livewire.types-of-movements.create-type-of-movement', compact('areas'));
    }
}
