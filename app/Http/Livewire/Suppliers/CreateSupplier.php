<?php

namespace App\Http\Livewire\Suppliers;

use Livewire\Component;
use App\Models\Locality;
use App\Models\Supplier;
use Illuminate\Support\Str;
use App\Models\IvaCondition;
use App\Http\Services\CUITService;

class CreateSupplier extends Component
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
        'createForm.business_name' => 'required|string|unique:suppliers,business_name',
        'createForm.iva_condition_id' => 'required|integer|exists:iva_conditions,id',
        'createForm.cuit' => 'required|unique:suppliers,cuit',
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
        'createForm.iva_condition_id' => 'condición de IVA',
        'createForm.cuit' => 'CUIT',
        'createForm.last_name' => 'apellido',
        'createForm.first_name' => 'nombre',
        'createForm.adress' => 'dirección',
        'createForm.locality_id' => 'localidad',
        'createForm.phone' => 'teléfono',
        'createForm.email' => 'email',
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
        $this->validate();

        $valid_cuit = CUITService::ValidateCUITCUIL($this->createForm['cuit']);
        if (!$valid_cuit) {
            $this->emit('error', 'El CUIT/CUIL ingresado no es válido.');
            return;
        }

        $this->createForm['slug'] = Str::slug($this->createForm['business_name']);

        Supplier::create($this->createForm);

        $this->reset('createForm');

        session()->flash('flash.banner', '¡Bien hecho! El proveedor se ha creado correctamente.');

        return redirect()->route('admin.suppliers.index');
    }

    public function render()
    {
        return view('livewire.suppliers.create-supplier');
    }
}
