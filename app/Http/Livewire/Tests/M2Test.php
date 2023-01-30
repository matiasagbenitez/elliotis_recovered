<?php

namespace App\Http\Livewire\Tests;

use App\Http\Services\M2Service;
use Livewire\Component;

class M2Test extends Component
{
    public $lots = [];
    public $selectedLot = '';

    public function mount()
    {
        $this->lots = \App\Models\Lot::whereHas('sublots', function ($query) {
            $query->where('available', true);
        })->get();
    }

    public function updatedSelectedLot($value)
    {
        $lot = \App\Models\Lot::find($value);
        $sublot = $lot->sublots()->where('available', true)->first();

        $m2s = M2Service::calculateSublotM2($sublot);
        dd($m2s);
    }

    public function render()
    {
        return view('livewire.tests.m2-test');
    }
}
