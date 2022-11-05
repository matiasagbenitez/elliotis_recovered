<?php

namespace App\Http\Livewire\Countries;

use App\Models\Country;
use Livewire\Component;

class EditCountry extends Component
{
    public $isOpen = 0;
    public $country, $country_id;
    public $editForm = ['name' => ''];

    protected $validationAttributes = [
        'editForm.name' => 'name'
    ];

    public function mount(Country $country)
    {
        $this->country = $country;
        $this->country_id = $country->id;
        $this->editForm['name'] = $country->name;
    }

    public function editCountry()
    {
        $this->resetInputFields();
        $this->openModal();
        $this->editForm['name'] = $this->country->name;
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

    public function update()
    {
        $this->validate([
            'editForm.name' => 'required|unique:countries,name,' . $this->country_id
        ]);
        $country = Country::find($this->country_id);
        $country->update($this->editForm);
        $this->reset('editForm');
        $this->closeModal();
        $this->emit('success', '¡El país se ha actualizado con éxito!');
        $this->emitTo('countries.index-countries', 'refresh');
    }

    public function render()
    {
        return view('livewire.countries.edit-country');
    }
}
