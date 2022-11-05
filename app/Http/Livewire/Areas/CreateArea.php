<?php

namespace App\Http\Livewire\Areas;

use App\Models\Area;
use Livewire\Component;

class CreateArea extends Component
{
    public $isOpen = 0;

    public $createForm = ['name' => ''];

    protected $rules = [
        'createForm.name' => 'required|string|unique:areas,name',
    ];

    protected $validationAttributes = [
        'createForm.name' => 'name'
    ];

    public function createArea()
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
        Area::create($this->createForm);
        $this->reset('createForm');
        $this->closeModal();
        $this->emit('success', '¡El área se ha creado con éxito!');
        $this->emitTo('areas.index-areas', 'refresh');
    }

    public function render()
    {
        return view('livewire.areas.create-area');
    }
}
