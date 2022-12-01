<?php

namespace App\Http\Livewire\Tasks;

use App\Models\TaskType;
use Livewire\Component;

class IndexTasks extends Component
{
    public $tasksTypes;

    public function mount()
    {
        $this->tasksTypes = TaskType::all();
    }

    public function render()
    {
        return view('livewire.tasks.index-tasks');
    }
}
