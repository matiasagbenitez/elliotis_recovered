<?php

namespace App\Http\Livewire\Lots;

use App\Models\Lot;
use App\Models\TypeOfTask;
use App\Models\User;
use Livewire\Component;

class LotsIndex extends Component
{
    public $lots, $typesOfTasks;
    public $stats = [];
    public $filters = [
        'task_name' => '',
        'date_from' => '',
        'date_to' => '',
    ];

    public function mount()
    {
        $this->lots = Lot::orderBy('created_at', 'desc')->get();
        $this->typesOfTasks = TypeOfTask::all();
        $this->getStats();
    }

    // public function updatedFilters($value)
    // {
    //     $this->lots = Lot::whereHas('task', function ($query) {
    //         $query->whereHas('typeOfTask', function ($query) {
    //             $query->where('name', 'like', '%' . $this->filters['task_name'] . '%');
    //         });
    //     });

    //     $this->getStats();
    // }

    public function getStats()
    {
        $this->stats = [];
        foreach ($this->lots as $lot) {
            $this->stats[] = [
                'lot' => $lot,
                'id' => $lot->id,
                'lot_code' => $lot->code,
                'task' => $lot->task->typeOfTask->name,
                'task_id' => $lot->task->id,
                'sublots_count' => $lot->sublots->count(),
                'created_at' => $lot->created_at->format('d-m-Y H:i'),
                'created_by' => User::find($lot->task->finished_by)->name,
            ];
        }
    }

    public function render()
    {
        return view('livewire.lots.lots-index');
    }
}
