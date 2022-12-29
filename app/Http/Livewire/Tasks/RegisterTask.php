<?php

namespace App\Http\Livewire\Tasks;

use App\Models\Task;
use App\Models\User;
use App\Models\Sublot;
use Livewire\Component;
use App\Models\TypeOfTask;
use App\Models\TrunkSublot;
use Illuminate\Support\Facades\Date;

class RegisterTask extends Component
{
    public $task, $type_of_task;
    public $sublots;
    public $info = [];
    public $movement, $transformation;
    protected $listeners = ['save'];

    public $inputSelects = [];
    public $inputSublots = [];

    public $taskOutputProducts = [];
    public $allOutputProducts = [];

    public $createForm = [
        'type_of_task' => '',
        'task_status_id' => '',
        'started_at' => '',
        'started_by' => '',
        'finished_at' => '',
        'finished_by' => '',
    ];

    public function mount(TypeOfTask $task_type, Task $task)
    {
        if (!$task || $task->started_by != auth()->user()->id || !$task->taskStatus->running) {
            abort(403);
        }

        $this->task = $task;
        $this->type_of_task = $task_type;
        $this->info = [
            'task_name' => $this->type_of_task->name,
            'user_who_started' => User::find($this->task->started_by)->name,
            'started_at' => Date::parse($this->task->started_at)->format('d-m-Y H:i'),
            'initial_phase' => $this->type_of_task->initialPhase->name,
            'origin_area' => $this->type_of_task->originArea->name,
            'final_phase' => $this->type_of_task->finalPhase->name,
            'destination_area' => $this->type_of_task->destinationArea->name,
        ];

        $this->getSublots();
    }


    // OBTENER SUBLOTES DE ENTRADA
    public function getSublots()
    {
        if ($this->type_of_task->initial_task) {
            $sublots = TrunkSublot::where('area_id', $this->type_of_task->origin_area_id)->get();
        } else {
            $sublots = Sublot::where('phase_id', $this->type_of_task->origin_phase_id)->where('area_id', $this->type_of_task->initial_area_id)->get();
        }

        // Preparamos la información para el select
        foreach ($sublots as $sublot) {
            $this->inputSublots[] = [
                'id' => $sublot->id,
                'actual_quantity' => $sublot->actual_quantity,
                'product_name' => $sublot->product->name,
                'lot_code' =>  'Lote: ' . $sublot->trunkLot ? $sublot->trunkLot->code . ' - ' . $sublot->trunkLot->purchase->supplier->business_name : $sublot->lot->code,
                'sublot_code' => $sublot->code ? 'Sublote: ' . $sublot->code : ''
            ];
        }

        $this->inputSelects = [
            ['sublot_id' => '', 'consumed_quantity' => 1]
        ];
    }

    // RESETEAR SELECT DE SUBLOTES
    public function resetInputSelects()
    {
        $this->reset('inputSelects');
    }

    // AGREGAR SUBLOTE
    public function addInputSelect()
    {
        if (count($this->inputSelects) == count($this->inputSublots)) {
            return;
        }

        if (!empty($this->inputSelects[count($this->inputSelects) - 1]['sublot_id']) || count($this->inputSelects) == 0) {
            $this->inputSelects[] = ['sublot_id' => '', 'consumed_quantity' => 1];
        }
    }

    // CONTROLAR REPETICIÓN DE SUBLOTES
    public function isSublotInInputSelect($id)
    {
        foreach ($this->inputSelects as $sublot) {
            if ($sublot['sublot_id'] == $id) {
                return true;
            }
        }
        return false;
    }

    // REMOVE INPUT PRODUCT
    public function removeInputSelect($index)
    {
        unset($this->inputSelects[$index]);
        $this->inputSelects = array_values($this->inputSelects);
    }

    public function save()
    {
        if ($this->type_of_task->initial_task) {
            dd($this->inputSelects);
        } else {
            dd('mmm');
        }
    }

    public function render()
    {
        return view('livewire.tasks.register-task');
    }
}
