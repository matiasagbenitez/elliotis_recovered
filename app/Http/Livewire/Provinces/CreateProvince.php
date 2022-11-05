<?php

namespace App\Http\Livewire\Provinces;

use App\Models\Country;
use App\Models\Province;
use Livewire\Component;

class CreateProvince extends Component
{
    public $isOpen = 0;
    public $createForm = ['name' => '', 'country_id' => ''];

    public $countries = [];

    protected $validationAttributes = [
        'createForm.name' => 'name',
        'createForm.country_id' => 'country'
    ];

    public function createProvince()
    {
        $this->resetInputFields();
        $this->openModal();
        $this->countries = Country::all();
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
        $this->createForm = ['name' => '', 'country_id' => ''];
        $this->resetErrorBag();
    }

    public function save()
    {
        $this->validate([
            'createForm.country_id' => 'required|exists:countries,id',
            'createForm.name' => 'required|unique:provinces,name,NULL,id,country_id,' . $this->createForm['country_id']
        ]);
        Province::create($this->createForm);
        $this->reset('createForm');
        $this->closeModal();
        $this->emit('success', '¡La provincia se ha creado con éxito!');
        $this->emitTo('provinces.index-provinces', 'refresh');
    }

    public function render()
    {
        return view('livewire.provinces.create-province');
    }
}
