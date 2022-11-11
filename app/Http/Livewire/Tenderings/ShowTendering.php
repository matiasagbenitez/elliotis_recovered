<?php

namespace App\Http\Livewire\Tenderings;

use App\Models\User;
use Livewire\Component;
use App\Models\Tendering;

class ShowTendering extends Component
{
    public $tender, $hashes = [], $user_who_cancelled = '', $user_who_finished = '';

    protected $listeners = ['finishTendering' => 'finishTendering'];

    public function mount(Tendering $tendering)
    {
        if ($tendering->cancelled_by) {
            $id = $tendering->cancelled_by;
            $this->user_who_cancelled = User::find($id)->name;
        } elseif ($tendering->finished_by) {
            $id = $tendering->finished_by;
            $this->user_who_finished = User::find($id)->name;
        }
        $this->tender = $tendering;
        $this->hashes = $tendering->hashes;
    }

    public function finishTendering(Tendering $tendering)
    {
        try {
            $tendering->update([
                'is_finished' => true,
                'finished_at' => now(),
                'finished_by' => auth()->user()->id,
            ]);
            $tendering->hashes()->update(['is_active' => false]);
            return redirect()->route('admin.tenderings.show-finished-tendering', $tendering);
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    public function render()
    {
        return view('livewire.tenderings.show-tendering');
    }
}
