<?php

namespace App\Http\Livewire\Lots;

use App\Models\Lot;
use Livewire\Component;

class SublotsIndex extends Component
{
    public $lot;

    public function mount(Lot $lot)
    {
        $this->lot = $lot;
    }

    public function render()
    {
        return view('livewire.lots.sublots-index');
    }
}
