<?php

namespace App\Http\Livewire\Localities;

use App\Models\Country;
use Livewire\Component;
use App\Models\Locality;
use App\Models\Province;

class EditLocality extends Component
{
    public $isOpen = 0;
    public $editForm = ['name' => '', 'postal_code' => '', 'province_id' => '', 'country_id' => ''];

    protected $validationAttributes = [
        'editForm.name' => 'name',
        'editForm.postal_code' => 'postal code',
        'editForm.province_id' => 'province',
        'editForm.country_id' => 'country'
    ];

    public $countries, $provinces = [];
    public $locality, $locality_id, $province_id, $country_id;

    public function mount(Locality $locality)
    {
        $this->locality = $locality;
        $this->locality_id = $locality->id;
        $this->editForm['name'] = $locality->name;
        $this->editForm['postal_code'] = $locality->postal_code;
        $this->editForm['province_id'] = $locality->province_id;
        $this->editForm['country_id'] = $locality->province->country_id;
        $this->getCountries();
        $this->updatedEditFormCountryId($this->editForm['country_id']);
    }

    public function editLocality()
    {
        $this->resetInputFields();
        $this->openModal();
        $this->editForm['name'] = $this->locality->name;
        $this->editForm['postal_code'] = $this->locality->postal_code;
        $this->editForm['province_id'] = $this->locality->province_id;
        $this->editForm['country_id'] = $this->locality->province->country_id;
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

    public function updatedEditFormCountryId($id)
    {
        $this->provinces = Province::where('country_id', $id)->get();
    }

    public function update()
    {
        $this->validate([
            'editForm.name' => 'required|unique:localities,name,' . $this->locality_id . ',id,province_id,' . $this->editForm['province_id'],
            'editForm.postal_code' => 'required|unique:localities,postal_code,' . $this->locality_id,
            'editForm.province_id' => 'required|exists:provinces,id',
        ]);

        if ($this->locality_id) {
            $locality = Locality::find($this->locality_id);
            $locality->update([
                'name' => $this->editForm['name'],
                'postal_code' => $this->editForm['postal_code'],
                'province_id' => $this->editForm['province_id']
            ]);
            $this->reset('editForm');
            $this->closeModal();
            $this->emit('success', '¡La localidad se ha actualizado con éxito!');
            $this->emitTo('localities.index-localities', 'refresh');
        }
    }

    public function render()
    {
        return view('livewire.localities.edit-locality');
    }
}
