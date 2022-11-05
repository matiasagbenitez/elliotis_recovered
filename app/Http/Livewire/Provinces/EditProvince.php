<?php

namespace App\Http\Livewire\Provinces;

use App\Models\Country;
use App\Models\Province;
use Livewire\Component;

class EditProvince extends Component
{
    public $isOpen = 0;
    public $editForm = ['name' => '', 'country_id' => ''];

    protected $validationAttributes = [
        'editForm.name' => 'name',
        'editForm.country_id' => 'country'
    ];

    public $countries;
    public $province, $province_id, $country_id;

    public function mount(Province $province)
    {
        $this->getCountries();
        $this->province = $province;
        $this->province_id = $province->id;
        $this->editForm['name'] = $province->name;
        $this->editForm['country_id'] = $province->country_id;
    }

    public function editProvince()
    {
        $this->resetInputFields();
        $this->openModal();
        $this->editForm['name'] = $this->province->name;
        $this->editForm['country_id'] = $this->province->country_id;
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
        $this->resetErrorBag();
    }

    public function getCountries()
    {
        $this->countries = Country::all();
    }

    public function update()
    {
        $this->validate([
            'editForm.country_id' => 'required|exists:countries,id',
            'editForm.name' => 'required|unique:provinces,name,' . $this->province_id . ',id,country_id,' . $this->editForm['country_id']
        ]);
        $province = Province::find($this->province_id);
        $province->update($this->editForm);
        $this->reset('editForm');
        $this->closeModal();
        $this->emit('success', '¡La provincia se ha actualizado con éxito!');
        $this->emitTo('provinces.index-provinces', 'refresh');
    }

    public function render()
    {
        return view('livewire.provinces.edit-province');
    }
}
