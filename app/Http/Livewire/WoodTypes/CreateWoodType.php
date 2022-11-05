<?php

namespace App\Http\Livewire\WoodTypes;

use Livewire\Component;
use App\Models\WoodType;

class CreateWoodType extends Component
{
    public $isOpen = 0;

    public $createForm = ['name' => ''];

    protected $rules = [
        'createForm.name' => 'required|unique:wood_types,name',
    ];

    protected $messages = [
        'createForm.name.required' => 'El campo nombre es obligatorio.',
        'createForm.name.unique' => 'El tipo de madera ya existe.',
    ];

    public function createWoodType()
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
        $this->createForm = ['name' => ''];
        $this->resetErrorBag();
    }

    public function save()
    {
        $this->validate();
        WoodType::create($this->createForm);
        $this->reset('createForm');
        $this->closeModal();
        $this->emit('success', '¡El tipo de madera se ha creado con éxito!');
        $this->emitTo('wood-types.index-wood-types', 'refresh');
    }

    public function render()
    {
        return view('livewire.wood-types.create-wood-type');
    }
}
