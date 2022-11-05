<?php

namespace App\Http\Livewire\Unities;

use App\Models\Unity;
use Livewire\Component;

class CreateUnity extends Component
{
    public $isOpen = 0;
    public $createForm = ['name' => '', 'unities' => ''];

    protected $rules = [
        'createForm.name' => 'required|unique:unities,name',
        'createForm.unities' => 'required|numeric|min:1|unique:unities,unities'
    ];

    protected $validationAttributes = [
        'createForm.name' => 'name',
        'createForm.unities' => 'unities'
    ];

    public function createUnity()
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
        $this->createForm = ['name' => '', 'unities' => ''];
        $this->resetErrorBag();
    }

    public function save()
    {
        $this->validate();
        Unity::create($this->createForm);
        $this->reset('createForm');
        $this->closeModal();
        $this->emit('success', '¡La unidad se ha creado con éxito!');
        $this->emitTo('unities.index-unities', 'refresh');
    }

    public function render()
    {
        return view('livewire.unities.create-unity');
    }
}
