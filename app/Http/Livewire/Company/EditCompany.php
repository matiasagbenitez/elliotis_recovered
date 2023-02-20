<?php

namespace App\Http\Livewire\Company;

use App\Models\Company;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class EditCompany extends Component
{
    use WithFileUploads;

    public $company, $newLogo;
    public $editForm = [
        'name' => '',
        'cuit' => '',
        'slogan' => '',
        'email' => '',
        'phone' => '',
        'address' => '',
        'cp' => '',
        'logo' => '',
    ];

    protected $rules = [
        'editForm.name' => 'required',
        'editForm.cuit' => 'required',
        'editForm.slogan' => 'nullable',
        'editForm.email' => 'required|email',
        'editForm.phone' => 'required',
        'editForm.address' => 'required',
        'editForm.cp' => 'required',
        'editForm.logo' => 'required',
        'newLogo' => 'nullable|image|max:1024',
    ];

    protected $validationAttributes = [
        'editForm.name' => 'nombre',
        'editForm.cuit' => 'cuit',
        'editForm.slogan' => 'slogan',
        'editForm.email' => 'correo electrónico',
        'editForm.phone' => 'teléfono',
        'editForm.address' => 'dirección',
        'editForm.cp' => 'código postal',
        'editForm.logo' => 'logo',
        'newLogo' => 'nuevo logo',
    ];

    public function mount()
    {
        $this->company = Company::first();
        $this->editForm = $this->company->toArray();
    }

    public function save()
    {
        // New logo?
        if ($this->newLogo) {
            $logo = $this->newLogo->store('public/img');
            $this->editForm['logo'] = str_replace('public/img', '', $logo);

            // Delete old logo
            if ($this->company->logo) {
                $oldLogo = str_replace('/', '', $this->company->logo);
                Storage::delete('public/img/' . $oldLogo);
            }
        } else {
            $this->editForm['logo'] = $this->company->logo;
        }

        $this->validate();
        $this->company->update($this->editForm);
        $this->emit('success', '¡Información de la empresa actualizada correctamente!');
        session()->flash('flash.banner', '¡Bien hecho! La información de la empresa ha sido actualizada correctamente.');

        return redirect()->route('admin.company.edit');
    }
    public function render()
    {
        return view('livewire.company.edit-company');
    }
}
