<?php

namespace App\Http\Livewire\Clients;

use App\Models\Client;
use Livewire\Component;
use App\Models\Locality;
use Illuminate\Support\Str;
use App\Models\IvaCondition;

class EditClient extends Component
{
    public $client;
    public $ivaConditions = [], $localities = [];

    public $editForm = [
        'business_name' => '',
        'iva_condition_id' => '',
        'cuit' => '',
        'last_name' => '',
        'first_name' => '',
        'adress' => '',
        'locality_id' => '',
        'phone' => '',
        'email' => '',
        'active' => true,
        'observations' => '',
    ];

    protected $validationAttributes = [
        'editForm.business_name' => 'business name',
        'editForm.iva_condition_id' => 'IVA condition',
        'editForm.cuit' => 'cuit',
        'editForm.last_name' => 'last name',
        'editForm.first_name' => 'first name',
        'editForm.adress' => 'adress',
        'editForm.locality_id' => 'locality',
        'editForm.phone' => 'phone',
        'editForm.email' => 'email',
        'editForm.active' => 'active',
        'editForm.observations' => 'observations',
    ];

    public function mount(Client $client)
    {
        $this->client = $client;
        $this->editForm['business_name'] = $client->business_name;
        $this->editForm['slug'] = Str::slug($client->business_name, '-');
        $this->editForm['iva_condition_id'] = $client->iva_condition_id;
        $this->editForm['cuit'] = $client->cuit;
        $this->editForm['last_name'] = $client->last_name;
        $this->editForm['first_name'] = $client->first_name;
        $this->editForm['adress'] = $client->adress;
        $this->editForm['locality_id'] = $client->locality_id;
        $this->editForm['phone'] = $client->phone;
        $this->editForm['email'] = $client->email;
        $this->editForm['active'] = $client->active;
        $this->editForm['observations'] = $client->observations;
        $this->ivaConditions = IvaCondition::all();
        $this->localities = Locality::orderBy('name', 'ASC')->get();
    }

    public function update()
    {
        $this->validate([
            'editForm.business_name' => 'required|string|unique:clients,business_name,' . $this->client->id,
            'editForm.iva_condition_id' => 'required|integer|exists:iva_conditions,id',
            'editForm.cuit' => 'required|unique:clients,cuit,' . $this->client->id,
            'editForm.last_name' => 'required|string|min:3',
            'editForm.first_name' => 'required|string|min:3',
            'editForm.adress' => 'required',
            'editForm.locality_id' => 'required|integer|exists:localities,id',
            'editForm.phone' => 'required',
            'editForm.email' => 'required',
            'editForm.active' => 'required|boolean',
            'editForm.observations' => 'nullable',
        ]);

        $this->client->update([
            'business_name' => $this->editForm['business_name'],
            'slug' => Str::slug($this->editForm['business_name'], '-'),
            'iva_condition_id' => $this->editForm['iva_condition_id'],
            'cuit' => $this->editForm['cuit'],
            'last_name' => $this->editForm['last_name'],
            'first_name' => $this->editForm['first_name'],
            'adress' => $this->editForm['adress'],
            'locality_id' => $this->editForm['locality_id'],
            'phone' => $this->editForm['phone'],
            'email' => $this->editForm['email'],
            'active' => $this->editForm['active'],
            'observations' => $this->editForm['observations'],
        ]);

        $this->reset('editForm');

        session()->flash('flash.banner', '¡Bien hecho! La información del cliente se actualizó correctamente.');

        return redirect()->route('admin.clients.index');
    }

    public function render()
    {
        return view('livewire.clients.edit-client');
    }
}
