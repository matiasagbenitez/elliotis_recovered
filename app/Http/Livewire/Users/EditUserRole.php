<?php

namespace App\Http\Livewire\Users;

use App\Models\User;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class EditUserRole extends Component
{
    public $user, $role_id;
    public $roles;

    public $isOpen = 0;

    public $editForm = [
        'role_id' => ''
    ];

    protected $validationAttributes = [
        'editForm.role_id' => 'rol'
    ];

    public function mount($user_id)
    {
        $this->roles = Role::where('name', '!=', 'sudo')->get();
        $this->user = User::find($user_id);
        $role = $this->user->roles->first();
        if ($role) {
            $this->editForm['role_id'] = $role->id;
        } else {
            $this->editForm['role_id'] = '';
        }
    }

    public function openModal()
    {
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    public function editRole()
    {
        $this->resetInputFields();
        $this->openModal();
    }

    private function resetInputFields()
    {
        $this->resetErrorBag();
    }

    public function update()
    {
        $this->user->syncRoles($this->editForm['role_id']);
        $this->closeModal();
        $this->emit('success', '¡El rol del usuario se ha actualizado con éxito!');
        $this->emitTo('users.users-index', 'refreshComponent');
    }

    public function render()
    {
        return view('livewire.users.edit-user-role');
    }
}
