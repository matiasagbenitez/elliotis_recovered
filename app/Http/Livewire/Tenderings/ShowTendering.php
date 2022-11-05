<?php

namespace App\Http\Livewire\Tenderings;

use App\Models\User;
use Livewire\Component;
use App\Models\Tendering;

class ShowTendering extends Component
{
    public $tender, $hashes = [], $user_who_cancelled = '';

    public function mount(Tendering $tendering)
    {
        if ($tendering->cancelled_by) {
            $id = $tendering->cancelled_by;
            $this->user_who_cancelled = User::find($id)->name;
        }
        $this->tender = $tendering;
        $this->hashes = $tendering->hashes;
    }

    public function render()
    {
        return view('livewire.tenderings.show-tendering');
    }
}
