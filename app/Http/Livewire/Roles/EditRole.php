<?php

namespace App\Http\Livewire\Roles;

use Livewire\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class EditRole extends Component
{
    public  $availablePermissions;
    public $role, $permissions;
    public $selectedPermissions = [];

    protected $listeners = ['togglePermission'];

    public function mount($role)
    {
        $this->availablePermissions = Permission::all();
        $this->role = Role::find($role);
        $this->permissions = $this->role->permissions()->pluck('name')->toArray();
        $this->selectedPermissions = collect($this->permissions)->map(function ($value, $key) {
            return [$value => true];
        })->collapse()->toArray();

        if (!$this->role) {
            abort(404);
        }
    }

    public function togglePermission($permission_name)
    {
        if (array_key_exists($permission_name, $this->selectedPermissions)) {
            unset($this->selectedPermissions[$permission_name]);
        } else {
            $this->selectedPermissions[$permission_name] = true;
        }
    }

    public function updateRole()
    {
        $this->role->syncPermissions(array_keys($this->selectedPermissions));

        session()->flash('flash.banner', '¡Rol actualizado exitosamente!');
        session()->flash('flash.bannerStyle', 'success');
        return redirect()->route('admin.roles.index');
    }

    public function render()
    {
        return view('livewire.roles.edit-role');
    }
}
