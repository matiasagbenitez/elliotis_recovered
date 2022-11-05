<?php

namespace App\Http\Livewire\Localities;

use App\Models\Country;
use Livewire\Component;
use App\Models\Locality;
use App\Models\Province;

class CreateLocality extends Component
{
    public $isOpen = 0;
    public $createForm = ['name' => '', 'postal_code' => '', 'province_id' => '', 'country_id' => ''];

    public $countries = [], $provinces = [];

    protected $validationAttributes = [
        'createForm.name' => 'name',
        'createForm.postal_code' => 'postal code',
        'createForm.province_id' => 'province',
        'createForm.country_id' => 'country'
    ];

    public function createLocality()
    {
        $this->resetInputFields();
        $this->openModal();
        $this->countries = Country::all();
    }

    public function updatedCreateFormCountryId($id)
    {
        $this->provinces = Province::where('country_id', $id)->get();
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
        $this->createForm = ['name' => '', 'postal_code' => '', 'province_id' => '', 'country_id' => ''];
        $this->resetErrorBag();
    }

    public function save()
    {
        $this->validate([
            'createForm.province_id' => 'required|exists:provinces,id',
            'createForm.name' => 'required|unique:localities,name,NULL,id,province_id,' . $this->createForm['province_id'],
            'createForm.postal_code' => 'required|unique:localities,postal_code,NULL,id,province_id,' . $this->createForm['province_id']
        ]);
        Locality::create([
            'name' => $this->createForm['name'],
            'postal_code' => $this->createForm['postal_code'],
            'province_id' => $this->createForm['province_id']
        ]);
        $this->reset('createForm');
        $this->closeModal();
        $this->emit('success', '¡La localidad se ha creado con éxito!');
        $this->emitTo('localities.index-localities', 'refresh');
    }

    public function render()
    {
        return view('livewire.localities.create-locality');
    }
}
