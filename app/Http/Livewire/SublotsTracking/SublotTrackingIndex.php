<?php

namespace App\Http\Livewire\SublotsTracking;

use App\Models\InputTaskDetail;
use App\Models\Sublot;
use Livewire\Component;

class SublotTrackingIndex extends Component
{
    public $sublotes = [];
    public $selectedSublot;

    protected $listeners = [
        'showSublot' => 'showSublot',
    ];

    public function mount()
    {
        $sublots = Sublot::where('area_id', 3)->take(10)->get();

        foreach ($sublots as $sublot) {
            $this->sublotes[] = [
                'id' => $sublot->id,
                'code' => $sublot->code,
                'product' => $sublot->product->name,
            ];
        }
    }

    public function showSublot()
    {
        if (!$this->selectedSublot) {
            $this->emit('error', 'Debe seleccionar un sublote');
            return;
        }

        $sublot = Sublot::find($this->selectedSublot);
        $task = $sublot->lot->task;

        // $historic = [
        //     'sublot_id' => $sublot->id,
        //     'sublot_code' => $sublot->code,
        //     'task_id' => $task->id,
        //     'task_name' => $task->typeOfTask->name,
        // ];

        $historic = $this->getTransformationHistory($sublot);
        dd($historic);
    }

    public function getTransformationHistory($sublot, $visitedSublots = [], $previousTasks = []) {
        // Si ya visitamos este sublote, detenemos la recursión
        if (in_array($sublot->id, $visitedSublots)) {
            return $previousTasks;
        }

        // Agregamos este sublote a la lista de sublotes visitados
        $visitedSublots[] = $sublot->id;

        // Obtenemos las tareas previas al sublote actual
        $previousTasks = $sublot->lot->task->typeOfTask->initial_task ? [] : $previousTasks;

        // Obtenemos las tareas que utilizaron este sublote como entrada
        $inputSublotDetails = InputTaskDetail::where('sublot_id', $sublot->id)->get();

        // Iteramos sobre las tareas para obtener los sublotes de entrada y llamar recursivamente a la función
        foreach ($inputSublotDetails as $inputSublotDetail) {
            $inputSublot = Sublot::find($inputSublotDetail->sublot_id);
            dd($inputSublot);
            // Obtenemos las tareas que generaron este sublote de entrada
            $inputSublotTasks = $inputSublot->lot->task->typeOfTask->initial_task
                ? [$inputSublot->lot->task]
                : $this->getTransformationHistory($inputSublot, $visitedSublots, $previousTasks);

            // Agregamos las tareas a la lista de tareas previas
            $previousTasks = array_merge($previousTasks, $inputSublotTasks);
        }

        return $previousTasks;
    }


    public function render()
    {
        return view('livewire.sublots-tracking.sublot-tracking-index');
    }
}
