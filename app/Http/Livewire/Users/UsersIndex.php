<?php

namespace App\Http\Livewire\Users;

use App\Models\User;
use Livewire\Component;

class UsersIndex extends Component
{
    public $users, $stats;
    protected $listeners = ['refreshComponent' => 'getStats'];

    public function mount()
    {
        $this->users = User::all();
        $this->getStats();
    }

    public function getStats()
    {
        foreach ($this->users as $user) {

            $role = $user->getRoleNames()->first();
            $role = str_replace('["', '', $role);
            $role = str_replace('"]', '', $role);

            $stats[] = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $role != '' ? $role : 'Ningún rol asignado',
            ];
        }
        $this->stats = $stats;
        $this->render();
    }

    public function render()
    {
        return view('livewire.users.users-index');
    }
}
