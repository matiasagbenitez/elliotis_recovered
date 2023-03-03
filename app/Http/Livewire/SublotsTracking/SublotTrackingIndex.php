<?php

namespace App\Http\Livewire\SublotsTracking;

use App\Models\InputTaskDetail;
use App\Models\Sublot;
use App\Models\User;
use Illuminate\Support\Facades\Date;
use Livewire\Component;

class SublotTrackingIndex extends Component
{
    public $selectedSublot;
    public $sublotStats = [];
    public $historic = [];

    public function mount()
    {
        $this->selectedSublot = 'S9868';
        $this->showSublot();
    }

    public function showSublot()
    {
        if (!$this->selectedSublot || $this->selectedSublot == 'null' || $this->selectedSublot == '') {
            $this->emit('error', 'Debe seleccionar un sublote');
            return;
        }

        $sublot = Sublot::where('code', $this->selectedSublot)->first();

        if (!$sublot) {
            $this->emit('error', 'El sublote no existe');
            return;
        }

        $this->getSublotStats($sublot);
        $this->getTransformationHistory($sublot->lot->task);
    }

    public function getSublotStats($sublot)
    {
        $this->sublotStats = [
            'id' => $sublot->id,
            'code' => $sublot->code,
            'lot' => $sublot->lot->code,
            'task' => $sublot->lot->task->typeOfTask->name,
            'started_at' => Date::parse($sublot->lot->task->started_at)->format('d/m/Y H:i'),
            'finished_at' => Date::parse($sublot->lot->task->finished_at)->format('d/m/Y H:i'),
            'started_by' => User::find($sublot->lot->task->started_by)->name,
            'finished_by' => User::find($sublot->lot->task->finished_by)->name,
            'product' => $sublot->product->name,
            'area' => $sublot->area->name,
            'initial_quantity' => $sublot->initial_quantity > 0 ? $sublot->initial_quantity . ' unidad(es)' : '',
            'actual_quantity' => $sublot->actual_quantity > 0 ? $sublot->actual_quantity . ' unidad(es)' : '',
            'initial_m2' => $sublot->initial_m2 > 0 ? '(' . $sublot->initial_m2 . 'm²)' : '',
            'actual_m2' => $sublot->actual_m2 > 0 ? '(' . $sublot->actual_m2 . 'm²)' : '',
            'available' => $sublot->available,
        ];
    }

    public function getTransformationHistory($task)
    {
        if (!$task) {
            return;
        }

        $this->historic[$task->id] = [
            'task_id' => $task->id,
            'name' => $task->typeOfTask->name,
            'started_at' => Date::parse($task->started_at)->format('d/m/Y H:i'),
            'started_by' => User::find($task->started_by)->name,
            'finished_at' => Date::parse($task->finished_at)->format('d/m/Y H:i'),
            'finished_by' => User::find($task->finished_by)->name,
            'sublots' => $task->inputSublotsDetails->map(function ($sublot) {
                return [
                    'id' => $sublot->id,
                    'code' => $sublot->code,
                    'product' => $sublot->product->name,
                    'm2' => $sublot->pivot->m2 > 0 ? '(' . $sublot->pivot->m2 . 'm²)' : 'x' . $sublot->pivot->consumed_quantity
                ];
            }),
        ];

        foreach ($task->inputSublotsDetails as $inputSublot) {
            $inputTask = $inputSublot->lot->task;
            $this->historic[$task->id][$inputTask->id] = [
                'task_id' => $inputTask->id,
                'name' => $inputTask->typeOfTask->name,
                'started_at' => Date::parse($task->started_at)->format('d/m/Y H:i'),
                'started_by' => User::find($task->started_by)->name,
                'finished_at' => Date::parse($task->finished_at)->format('d/m/Y H:i'),
                'finished_by' => User::find($task->finished_by)->name,
                'sublots' => $inputTask->inputSublotsDetails->map(function ($sublot) {
                    return [
                        'id' => $sublot->id,
                        'code' => $sublot->code,
                        'product' => $sublot->product->name,
                        'purchase' => false,
                        'm2' => $sublot->pivot->m2 > 0 ? '(' . $sublot->pivot->m2 . 'm²)' : 'x' . $sublot->pivot->consumed_quantity
                    ];
                }),
            ];

            if ($inputTask->typeOfTask->initial_task) {
                $this->historic[$task->id][$inputTask->id] = [
                    'task_id' => $inputTask->id,
                    'name' => $inputTask->typeOfTask->name,
                    'sublots' => $inputTask->trunkSublots->map(function ($trunkSublot) {
                        return [
                            'id' => $trunkSublot->id,
                            'code' => $trunkSublot->trunkLot->purchase->supplier->business_name,
                            'product' => $trunkSublot->product->name,
                            'purchase' => true,
                            'm2' => $trunkSublot->trunkLot->purchase->id
                        ];
                    }),
                ];
            } else {
                $this->getTransformationHistory($inputTask);
            }
        }
    }

    public function resetSublot()
    {
        $this->selectedSublot = null;
        $this->historic = [];
        $this->sublotStats = [];
    }

    public function render()
    {
        return view('livewire.sublots-tracking.sublot-tracking-index');
    }
}
