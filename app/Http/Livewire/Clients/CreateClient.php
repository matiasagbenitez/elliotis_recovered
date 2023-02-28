<?php

namespace App\Http\Livewire\Clients;

use App\Http\Services\CUITService;
use validateCUITCUIL;
use App\Models\Client;
use Livewire\Component;
use App\Models\Locality;
use Illuminate\Support\Str;
use App\Models\IvaCondition;

class CreateClient extends Component
{
    public $ivaConditions = [], $localities = [];

    public $createForm = [
        'business_name' => '',
        'slug' => '',
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

    protected $rules = [
        'createForm.business_name' => 'required|string|unique:clients,business_name',
        'createForm.iva_condition_id' => 'required|integer|exists:iva_conditions,id',
        'createForm.cuit' => 'required|unique:clients,cuit',
        'createForm.last_name' => 'required|string|min:3',
        'createForm.first_name' => 'required|string|min:3',
        'createForm.adress' => 'required',
        'createForm.locality_id' => 'required|integer|exists:localities,id',
        'createForm.phone' => 'required',
        'createForm.email' => 'required',
        'createForm.active' => 'required|boolean',
        'createForm.observations' => 'nullable',
    ];

    protected $validationAttributes = [
        'createForm.business_name' => 'razón social',
        'createForm.iva_condition_id' => 'condición ante IVA',
        'createForm.cuit' => 'cuit',
        'createForm.last_name' => 'nombre',
        'createForm.first_name' => 'apellido',
        'createForm.adress' => 'dirección',
        'createForm.locality_id' => 'localidad',
        'createForm.phone' => 'teéfono',
        'createForm.email' => 'correo electrónico',
        'createForm.active' => 'activo',
        'createForm.observations' => 'observaciones',
    ];

    public function mount()
    {
        $this->ivaConditions = IvaCondition::all();
        $this->localities = Locality::orderBy('name', 'ASC')->get();
    }

    public function save()
    {

        $valid_cuit = CUITService::ValidateCUITCUIL($this->createForm['cuit']);
        if (!$valid_cuit) {
            $this->emit('error', 'El CUIT/CUIL ingresado no es válido.');
            return;
        }

        $this->validate();

        $this->createForm['slug'] = Str::slug($this->createForm['business_name']);
        Client::create($this->createForm);

        $this->reset('createForm');

        session()->flash('flash.banner', '¡Bien hecho! El cliente se creó correctamente.');

        return redirect()->route('admin.clients.index');
    }


    public function render()
    {
        return view('livewire.clients.create-client');
    }
}
