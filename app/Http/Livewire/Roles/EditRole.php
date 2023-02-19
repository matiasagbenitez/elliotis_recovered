<?php

namespace App\Http\Livewire\Roles;

use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class EditRole extends Component
{
    public $role, $permissions;
    public $selectedPermissions = [];

    public function mount($role)
    {
        $this->role = Role::find($role);
        $this->permissions = $this->role->permissions()->pluck('name')->toArray();
        $this->selectedPermissions = collect($this->permissions)->mapWithKeys(function ($permission) {
            return [$permission => $this->role->hasPermissionTo($permission)];
        })->toArray();

        if (!$this->role) {
            abort(404);
        }
    }

    public function updateRole()
    {
        $permissions = collect($this->selectedPermissions)->map(function ($value, $key) {
            if (is_array($value)) {
                return collect($value)->map(function ($value, $key) {
                    return $value ? $key : null;
                })->filter();
            }

            return $value ? $key : null;
        })->flatten()->toArray();
    }

    public function render()
    {
        $availablePermissions = Permission::all();
        return view('livewire.roles.edit-role', compact('availablePermissions'));
    }
}
