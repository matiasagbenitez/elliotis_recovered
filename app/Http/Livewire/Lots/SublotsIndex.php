<?php

namespace App\Http\Livewire\Lots;

use App\Models\Lot;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Date;

class SublotsIndex extends Component
{
    public $lot;
    public $lotData;

    public function mount(Lot $lot)
    {
        $this->lot = $lot;
        $this->lotData = [
            'taskName' => $lot->task->typeOfTask->name,
            'finishedBy' => User::find($lot->task->finished_by)->name,
            'finishedAt' => Date::parse($lot->task->finished_at)->format('d-m-Y H:i'),
            'taskId' => $lot->task->id
        ];
    }

    public function render()
    {
        return view('livewire.lots.sublots-index');
    }
}
