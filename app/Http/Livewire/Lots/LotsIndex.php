<?php

namespace App\Http\Livewire\Lots;

use App\Models\Lot;
use App\Models\User;
use Livewire\Component;
use App\Models\TypeOfTask;
use Livewire\WithPagination;

class LotsIndex extends Component
{
    use WithPagination;

    public $lots = [], $typesOfTasks;
    public $stats = [];

    public $filters = [
        'type_of_task' => '',
        'sublots_availability' => 'all',
        'fromDate' => '',
        'toDate' => '',
    ];

    public function mount()
    {
        $this->lots = Lot::orderBy('created_at', 'desc')->get();
        $this->typesOfTasks = TypeOfTask::all();
        $this->getStats();
    }

    public function updatedFilters()
    {
        $this->lots = Lot::orderBy('created_at', 'desc')->get();

        // Filter by type of task
        if ($this->filters['type_of_task'] != '') {
            $this->lots = Lot::whereHas('task', function ($query) {
                $query->where('type_of_task_id', $this->filters['type_of_task']);
            })->orderBy('created_at', 'desc')->get();
        }

        // Filter by sublots availability
        if ($this->filters['sublots_availability'] != 'all') {
            $this->lots = $this->lots->filter(function ($lot) {
                if ($this->filters['sublots_availability'] == 'available') {
                    return $lot->sublots->where('available', true)->count() == $lot->sublots->count();
                } elseif ($this->filters['sublots_availability'] == 'unavailable') {
                    return $lot->sublots->where('available', true)->count() == 0;
                } elseif ($this->filters['sublots_availability'] == 'partially') {
                    return $lot->sublots->where('available', true)->count() > 0 && $lot->sublots->where('available', true)->count() < $lot->sublots->count();
                }
            });
        }

        // Filter by date
        if ($this->filters['fromDate'] != '' && $this->filters['toDate'] != '') {
            $this->lots = $this->lots->filter(function ($lot) {
                return $lot->created_at->between($this->filters['fromDate'], $this->filters['toDate']);
            });
        } elseif ($this->filters['fromDate'] != '') {
            $this->lots = $this->lots->filter(function ($lot) {
                return $lot->created_at >= $this->filters['fromDate'];
            });
        } elseif ($this->filters['toDate'] != '') {
            $this->lots = $this->lots->filter(function ($lot) {
                return $lot->created_at <= $this->filters['toDate'];
            });
        }

        $this->getStats();
    }


    public function getStats()
    {
        $this->stats = [];

        foreach ($this->lots as $lot) {

            $sublots_availability = 0;

            if ($lot->sublots->where('available', true)->count() == 0) {
                $sublots_availability = 0;
            } elseif ($lot->sublots->where('available', true)->count() == $lot->sublots->count()) {
                $sublots_availability = 1;
            } else {
                $sublots_availability = 2;
            }

            $m2 = 0;
            foreach ($lot->sublots as $sublot) {
                $m2 += $sublot->initial_m2;
            }

            $this->stats[] = [
                'id' => $lot->id,
                'lot_code' => $lot->code,
                'task' => $lot->task->typeOfTask->name,
                'task_id' => $lot->task->id,
                'm2' => $m2 == 0 ? 'N/A' : $m2 . ' m2',
                'sublots_count' => $lot->sublots->count() . ' sublotes',
                'sublots_availability' => $sublots_availability,
                'created_at' => $lot->created_at->format('d-m-Y H:i'),
            ];
        }
    }

    public function resetFilters()
    {
        $this->filters = [
            'type_of_task' => '',
            'sublots_availability' => 'all',
            'fromDate' => '',
            'toDate' => '',
        ];

        $this->lots = Lot::orderBy('created_at', 'desc')->get();
        $this->getStats();

    }

    public function render()
    {
        return view('livewire.lots.lots-index');
    }
}
