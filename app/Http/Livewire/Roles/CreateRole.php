<?php

namespace App\Http\Livewire\Roles;

use Livewire\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CreateRole extends Component
{
    public $availablePermissions, $selectedPermissions = [];
    protected $listeners = ['togglePermission'];

    public $createForm = [
        'name' => null,
        'guard_name' => 'web',
    ];

    protected $validationAttributes = [
        'createForm.name' => 'nombre',
        'createForm.permissions' => 'permisos'
    ];

    public function mount()
    {
        $this->availablePermissions = Permission::all();
    }

    public function togglePermission($permission_name)
    {
        if (array_key_exists($permission_name, $this->selectedPermissions)) {
            unset($this->selectedPermissions[$permission_name]);
        } else {
            $this->selectedPermissions[$permission_name] = true;
        }
    }

    public function createRole()
    {
        try {
            $this->createForm['permissions'] = array_keys($this->selectedPermissions);

            $this->validate([
                'createForm.name' => 'required|unique:roles,name',
                'createForm.permissions' => 'required',
            ]);

            $role = Role::create($this->createForm);
            $role->syncPermissions($this->createForm['permissions']);

            session()->flash('flash.banner', '¡Rol creado exitosamente!');
            session()->flash('flash.bannerStyle', 'success');
            return redirect()->route('admin.roles.index');
        } catch (\Throwable $th) {
            $this->emit('error', '¡Error!', 'Ocurrió un error al crear el rol. Intente nuevamente.');
        }
    }

    public function render()
    {
        return view('livewire.roles.create-role');
    }
}
