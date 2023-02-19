<?php

namespace App\Http\Livewire\Roles;

use Livewire\Component;
use Spatie\Permission\Models\Role;

class RolesIndex extends Component
{
    public $roles, $stats;

    public function mount()
    {
        $this->roles = Role::all();
        $this->getStats();
    }

    public function getStats()
    {
        foreach ($this->roles as $role) {
            $this->stats[] = [
                'id' => $role->id,
                'name' => $role->name,
                'users' => $role->users->count() != 1 ? $role->users->count() . ' usuarios' : $role->users->count() . ' usuario',
                'permissions' => $role->permissions->count() != 1 ? $role->permissions->count() . ' permisos' : $role->permissions->count() . ' permiso',
            ];
        }
    }

    public function render()
    {
        return view('livewire.roles.roles-index');
    }
}
