<?php

namespace App\Http\Livewire\IvaConditions;

use Livewire\Component;
use App\Models\IvaCondition;

class CreateIvaCondition extends Component
{
    public $isOpen = 0;
    public $createForm = ['name' => '', 'discriminate' => ''];

    protected $rules = [
        'createForm.name' => 'required|unique:iva_conditions,name',
        'createForm.discriminate' => 'required|boolean'
    ];

    protected $validationAttributes = [
        'createForm.name' => 'name',
        'createForm.discriminate' => 'discriminate'
    ];

    public function createIvaCondition()
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
        $this->createForm = ['name' => '', 'discriminate' => ''];
        $this->resetErrorBag();
    }

    public function save()
    {
        $this->validate();
        IvaCondition::create($this->createForm);
        $this->reset('createForm');
        $this->closeModal();
        $this->emit('success', '¡La condición de IVA se ha creado con éxito!');
        $this->emitTo('iva-conditions.index-iva-conditions', 'refresh');
    }

    public function render()
    {
        return view('livewire.iva-conditions.create-iva-condition');
    }
}
