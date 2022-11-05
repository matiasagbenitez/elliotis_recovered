<?php

namespace App\Http\Livewire\Countries;

use App\Models\Country;
use Livewire\Component;

class CreateCountry extends Component
{
    public $isOpen = 0;
    public $createForm = ['name' => ''];

    protected $rules = [
        'createForm.name' => 'required|unique:countries,name'
    ];

    protected $validationAttributes = [
        'createForm.name' => 'name'
    ];

    public function createCountry()
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
        Country::create($this->createForm);
        $this->reset('createForm');
        $this->closeModal();
        $this->emit('success', '¡El país se ha creado con éxito!');
        $this->emitTo('countries.index-countries', 'refresh');
    }

    public function render()
    {
        return view('livewire.countries.create-country');
    }
}
