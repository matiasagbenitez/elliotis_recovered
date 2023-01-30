<?php

namespace App\Http\Livewire\Tasks;

use App\Models\Task;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Date;

class ShowTask extends Component
{
    public $task, $type_of_task;
    public $taskData = [];
    public $initial, $transformation, $movement, $movement_transformation;
    public $user_who_cancelled = null;

    public $inputData = [];
    public $outputData = [];

    public function mount(Task $task)
    {
        $this->task = $task;
        $this->type_of_task = $task->typeOfTask;

        $this->taskData = [
            'task_id' => $task->id,
            'type_of_task_name' => $task->typeOfTask->name,
            'started_by' => User::find($task->started_by)->name,
            'started_at' => Date::parse($task->started_at)->format('d-m-Y H:i'),
            'finished_by' => User::find($task->finished_by)->name,
            'finished_at' => Date::parse($task->finished_at)->format('d-m-Y H:i'),
            'origin_area' => $task->typeOfTask->originArea->name,
            'initial_phase' => $task->typeOfTask->initialPhase->name,
            'destination_area' => $task->typeOfTask->destinationArea->name,
            'final_phase' => $task->typeOfTask->finalPhase->name,
        ];

        if ($task->cancelled_by) {
            $this->user_who_cancelled = User::find($task->cancelled_by)->name;
        }

        if ($this->type_of_task->initial_task) {
            $this->initial = true;
            $this->show_initial();
        } else if ($this->type_of_task->movement && !$this->type_of_task->transformation) {
            $this->movement = true;
            $this->show_movement();
        } else if ($this->type_of_task->transformation && !$this->type_of_task->movement) {
            $this->transformation = true;
            $this->show_transformation();
        } else if ($this->type_of_task->transformation && $this->type_of_task->movement) {
            $this->show_movement_transformation();
            $this->movement_transformation = true;
        } else {
            abort(403);
        }
    }

    public function show_initial()
    {
        foreach ($this->task->trunkSublots as $sublot) {
            $this->inputData [] = [
                'lot_code' => $sublot->trunkLot->code,
                'sublot_code' => $sublot->trunkLot->purchase->supplier->business_name,
                'product_name' => $sublot->product->name,
                'quantity' => $sublot->initial_quantity,
                'm2' => $sublot->m2 > 0 ? $sublot->m2 . ' m2' : 'N/A'
            ];
        }

        foreach ($this->task->outputSublotsDetails as $sublot) {
            $this->outputData [] = [
                'lot_code' => $sublot->lot->code,
                'sublot_code' => $sublot->code,
                'product_name' => $sublot->product->name,
                'quantity' => $sublot->initial_quantity,
                'm2' => $sublot->m2 > 0 ? $sublot->m2 . ' m2' : 'N/A'
            ];
        }
    }

    public function show_transformation()
    {
        foreach ($this->task->inputSublotsDetails as $sublot) {
            $this->inputData [] = [
                'lot_code' => $sublot->lot->code,
                'sublot_code' => $sublot->code,
                'product_name' => $sublot->product->name,
                'quantity' => $sublot->pivot->consumed_quantity,
                'm2' => $sublot->pivot->m2 > 0 ? $sublot->pivot->m2 . ' m2' : 'N/A',
            ];
        }

        foreach ($this->task->outputSublotsDetails as $sublot) {
            $this->outputData [] = [
                'lot_code' => $sublot->lot->code,
                'sublot_code' => $sublot->code,
                'product_name' => $sublot->product->name,
                'quantity' => $sublot->initial_quantity,
                'm2' => $sublot->pivot->m2 > 0 ? $sublot->pivot->m2 . ' m2' : 'N/A',
            ];
        }
    }

    public function show_movement()
    {
        foreach ($this->task->inputSublotsDetails as $sublot) {
            $this->inputData [] = [
                'lot_code' => $sublot->lot->code,
                'sublot_code' => $sublot->code,
                'product_name' => $sublot->product->name,
                'quantity' => $sublot->initial_quantity,
                'm2' => $sublot->initial_m2 > 0 ? $sublot->initial_m2 . ' m2' : 'N/A',
            ];
        }

        foreach ($this->task->outputSublotsDetails as $sublot) {
            $this->outputData [] = [
                'lot_code' => $sublot->lot->code,
                'sublot_code' => $sublot->code,
                'product_name' => $sublot->product->name,
                'quantity' => $sublot->pivot->produced_quantity,
                'm2' => $sublot->pivot->m2 > 0 ? $sublot->pivot->m2 . ' m2' : 'N/A',
            ];
        }
    }

    public function show_movement_transformation()
    {
        foreach ($this->task->inputSublotsDetails as $sublot) {
            $this->inputData [] = [
                'lot_code' => $sublot->lot->code,
                'sublot_code' => $sublot->code,
                'product_name' => $sublot->product->name,
                'quantity' => $sublot->initial_quantity,
                'm2' => $sublot->initial_m2 > 0 ? $sublot->initial_m2 . ' m2' : 'N/A',
            ];
        }

        foreach ($this->task->outputSublotsDetails as $sublot) {
            $this->outputData [] = [
                'lot_code' => $sublot->lot->code,
                'sublot_code' => $sublot->code,
                'product_name' => $sublot->product->name,
                'quantity' => $sublot->pivot->produced_quantity,
                'm2' => $sublot->pivot->m2 > 0 ? $sublot->pivot->m2 . ' m2' : 'N/A',
            ];
        }
    }

    public function render()
    {
        return view('livewire.tasks.show-task');
    }
}
