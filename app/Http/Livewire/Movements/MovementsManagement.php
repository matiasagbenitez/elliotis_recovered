<?php

namespace App\Http\Livewire\Movements;

use App\Models\User;
use Livewire\Component;
use App\Models\Movement;
use Livewire\WithPagination;
use App\Models\MovementStatus;
use App\Models\TypeOfMovement;
use Illuminate\Support\Facades\Date;

class MovementsManagement extends Component
{
    use WithPagination;

    public $movement_type_name, $type_of_movement_id, $running_movement;
    public $movements, $employees, $statuses;

    protected $listeners = ['startNewMovement', 'finishMovement', 'render'];

    public $filters = [
        'employee_id' => null,
        'movement_status_id' => null,
        'fromDate' => null,
        'toDate' => null,
    ];

    public function mount(TypeOfMovement $movementType)
    {
        $this->movement_type_name = $movementType->name;
        $this->type_of_movement_id = $movementType->id;
        $this->running_movement = $this->getRunningMovement();
        $this->employees = User::all();
        $this->statuses = MovementStatus::all();
        $this->movements = $this->getMovements();
    }

    public function updatedFilters()
    {
        $this->resetPage();
        $this->movements = $this->getMovements();
    }

    public function resetFilters()
    {
        $this->reset('filters');
        $this->updatedFilters();
    }

    public function getMovements()
    {
        if ($this->running_movement) {
            $allMovements = Movement::filter($this->filters)->where('type_of_movement_id', $this->type_of_movement_id)->latest()->paginate(10);
        } else {
            $allMovements = Movement::filter($this->filters)->where('type_of_movement_id', $this->type_of_movement_id)->orderBy('movement_status_id', 'asc')->orderBy('updated_at', 'desc')->paginate(10);
        }

        $movements = [];

        foreach ($allMovements as $movement) {
            $movements[] = [
                'id' => $movement->id,
                'status' => $movement->movement_status_id,
                'started_at' => Date::parse($movement->started_at)->format('d-m-Y H:i'),
                'started_by' => User::find($movement->started_by)->name,
                'finished_at' => $movement->finished_at ? Date::parse($movement->finished_at)->format('d-m-Y H:i') : null,
                'finished_by' => $movement->finished_by ? User::find($movement->finished_by)->name : null,
            ];
        }

        return $movements;
    }

    public function getRunningMovement()
    {
        $runningMovement = Movement::where('type_of_movement_id', $this->type_of_movement_id)->where('movement_status_id', 2)->first();
        return $runningMovement;
    }

    public function startNewMovement()
    {
        try {
            $createForm = [
                'type_of_movement_id' => $this->type_of_movement_id,
                'movement_status_id' => 2,
                'started_at' => Date::now(),
                'started_by' => auth()->user()->id,
            ];

            Movement::create($createForm);
            $this->running_task = $this->getRunningMovement();
            $this->tasks = $this->getMovements();
            $this->emit('success', '¡Tarea de tipo: '. $this->movement_type_name .' iniciada correctamente!');
        } catch (\Throwable $th) {
            dd($th);
            $this->emit('error', '¡Error al iniciar la tarea de tipo: '. $this->movement_type_name .'!');
        }
        $this->render();
    }

    public function finishMovement($id)
    {
        try {
            // DE MOMENTO
            $movement = Movement::find($id);
            $movement->movement_status_id = 3;
            $movement->finished_at = Date::now();
            $movement->finished_by = auth()->user()->id;
            $movement->save();

            return redirect()->route('admin.movements.register', ['movement' => $id]);
        } catch (\Throwable $th) {
            dd($th);
            $this->emit('error', '¡Error al finalizar la tarea de tipo: '. $this->movement_type_name .'!');
        }
        $this->render();
    }

    public function showFinishedMovement($id)
    {
        try {
            return redirect()->route('admin.movements.show', ['movement' => $id]);
        } catch (\Throwable $th) {
            $this->emit('error', '¡Error al mostrar la tarea de tipo: '. $this->movement_type_name .'!');
        }
        $this->render();
    }

    public function render()
    {
        return view('livewire.movements.movements-management');
    }
}
