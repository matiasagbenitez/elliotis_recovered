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
            'taskId' => $lot->task->id,
            'taskName' => $lot->task->typeOfTask->name,
            'startedBy' => User::find($lot->task->started_by)->name,
            'startedAt' => Date::parse($lot->task->started_at)->format('d-m-Y H:i'),
            'finishedBy' => User::find($lot->task->finished_by)->name,
            'finishedAt' => Date::parse($lot->task->finished_at)->format('d-m-Y H:i'),
            'sublots_count' => $lot->sublots->count(),
            'initial_production' => $lot->sublots->sum('initial_m2') . ' m2',
            'actual_m2' => $lot->sublots->sum('actual_m2') . ' m2',
        ];
    }

    public function render()
    {
        return view('livewire.lots.sublots-index');
    }
}
