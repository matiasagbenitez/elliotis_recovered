<?php

namespace App\Http\Livewire\ProductionReport;

use App\Models\Task;
use App\Models\TaskStatus;
use App\Models\TypeOfTask;
use App\Models\User;
use Illuminate\Support\Facades\Date;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Pagination\LengthAwarePaginator;

class IndexProductionReport extends Component
{
    use WithPagination;
    public $filters = [
        'category' => null,
        'type_of_task_id' => null,
        'employee_id' => null,
        'from_date' => null,
        'to_date' => null,
    ];

    public $types_of_tasks, $employees, $statuses;

    public function mount()
    {
        $this->types_of_tasks = TypeOfTask::all();
        $this->employees = User::all();
        $this->statuses = TaskStatus::all();
    }

    public function updatedFilters()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->filters = [
            'category' => null,
            'type_of_task_id' => null,
            'from_date' => null,
            'to_date' => null,
        ];
    }

    public function render()
    {
        $category = $this->filters['category'] != '' ? $this->filters['category'] : null;
        $type_of_task_id = $this->filters['type_of_task_id'] != '' ? $this->filters['type_of_task_id'] : null;
        $from_date = $this->filters['from_date'] != '' ? $this->filters['from_date'] : null;
        $to_date = $this->filters['to_date'] != '' ? $this->filters['to_date'] : null;

        $tasks = Task::where('task_status_id', '!=', 1)

            ->when($type_of_task_id, function ($query) use ($type_of_task_id) {
                return $query->where('type_of_task_id', $type_of_task_id);
            })

            ->when($category, function ($query) use ($category) {
                switch ($category) {
                    case 'movement':
                        return $query->whereHas('typeOfTask', function ($query) {
                            return $query->where('movement', true)->where('transformation', false);
                        });
                        break;
                    case 'transformation':
                        return $query->whereHas('typeOfTask', function ($query) {
                            return $query->where('transformation', true)->where('movement', false);
                        });
                        break;
                    case 'movement_transformation':
                        return $query->whereHas('typeOfTask', function ($query) {
                            return $query->where('movement', true)->where('transformation', true);
                        });
                        break;
                }
            })

            ->when($from_date, function ($query) use ($from_date) {
                return $query->whereDate('started_at', '>=', $from_date);
            })

            ->when($to_date, function ($query) use ($to_date) {
                return $query->whereDate('finished_at', '<=', $to_date);
            })

            ->orderBy('finished_at', 'desc')
            ->paginate(15);

        return view('livewire.production-report.index-production-report', compact('tasks'));
    }
}
