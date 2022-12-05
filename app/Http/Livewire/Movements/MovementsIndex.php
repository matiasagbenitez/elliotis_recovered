<?php

namespace App\Http\Livewire\Movements;

use App\Models\User;
use Livewire\Component;
use App\Models\TypeOfMovement;
use Illuminate\Support\Facades\Date;

class MovementsIndex extends Component
{
    public $movementsTypes, $stats;

    public function mount()
    {
        $this->movementsTypes = TypeOfMovement::all();
        $this->stats = $this->getStats();
    }

    public function getStats()
    {
        $stats = [];

        foreach ($this->movementsTypes as $movementType) {

            $running_movement = $movementType->movements()->where('movement_status_id', 2)->first();
            $running_movement ? $movement = $running_movement : $movement = $movementType->movements()->latest()->first();

            $stats[] = [
                'id' => $movementType->id,
                'movement_id' => $running_movement ? $movement->id : null,
                'name' => $movementType->name,
                'icon' => $movementType->icon,
                'running_movement' => $movement ? ($movement->movement_status_id == 2 ? true : false) : null,
                'user' =>
                    $movement ?
                    (
                        $movement->movement_status_id == 2
                        ?
                        User::find($movement->started_by)->name
                        :
                        User::find($movement->finished_by)->name
                    ) : null,
                'date' =>
                $movement ?
                (
                    $movement->movement_status_id == 2
                    ?
                    Date::parse($movement->started_at)->format('d/m/Y H:i')
                    :
                    Date::parse($movement->finished_at)->format('d/m/Y H:i')
                ) : null,
            ];
        }

        // dd($stats);

        return $stats;
    }

    public function render()
    {
        return view('livewire.movements.movements-index');
    }
}
