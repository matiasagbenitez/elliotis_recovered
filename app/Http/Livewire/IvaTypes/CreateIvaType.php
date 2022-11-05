<?php

namespace App\Http\Livewire\IvaTypes;

use Livewire\Component;
use App\Models\IvaTypes;

class CreateIvaType extends Component
{
    public $isOpen = 0;
    public $createForm = ['name' => '', 'percentage' => ''];

    protected $rules = [
        'createForm.name' => 'required|unique:iva_types,name',
        'createForm.percentage' => 'required|numeric|unique:iva_types,percentage|between:0,100'
    ];

    protected $validationAttributes = [
        'createForm.name' => 'name',
        'createForm.percentage' => 'percentage'
    ];

    public function createIvaType()
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
        $this->createForm = ['name' => '', 'percentage' => ''];
        $this->resetErrorBag();
    }

    public function save()
    {
        $this->validate();
        IvaTypes::create($this->createForm);
        $this->reset('createForm');
        $this->closeModal();
        $this->emitTo('iva-types.index-iva-types', 'refresh');
        session()->flash('flash.banner', '¡Bien hecho! El tipo de IVA se creó correctamente.');
    }

    public function render()
    {
        return view('livewire.iva-types.create-iva-type');
    }
}
