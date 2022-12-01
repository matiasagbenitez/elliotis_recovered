<?php

namespace App\Http\Livewire\Phases;

use App\Models\Phase;
use Livewire\Component;

class CreatePhase extends Component
{
    public $isOpen = 0;

    public $createForm = ['name' => ''];

    protected $rules = [
        'createForm.name' => 'required|string|unique:phases,name',
    ];

    protected $validationAttributes = [
        'createForm.name' => 'name'
    ];

    public function createPhase()
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
        Phase::create($this->createForm);
        $this->reset('createForm');
        $this->closeModal();
        $this->emit('success', '¡La fase se ha creado con éxito!');
        $this->emitTo('phases.index-phases', 'refresh');
    }

    public function render()
    {
        return view('livewire.phases.create-phase');
    }
}
