<?php

namespace App\Http\Livewire\TypesOfMovements;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\TypeOfMovement;

class TypesOfMovementsIndex extends Component
{
    use WithPagination;
    public $search;

    protected $listeners = ['delete', 'render'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function delete(TypeOfMovement $typeOfMovement)
    {
        try {
            $typeOfMovement->delete();
            $this->emit('success', 'Tipo de movimiento eliminado con éxito');
        } catch (\Throwable $th) {
            $this->emit('error', 'No se pudo eliminar el tipo de movimiento');
        }
    }

    public function render()
    {
        $types_of_movements = TypeOfMovement::where('name', 'LIKE', '%' . $this->search . '%')
            ->orWhereHas('originArea', function ($query) {
                $query->where('name', 'LIKE', '%' . $this->search . '%');
            })
            ->orWhereHas('destinationArea', function ($query) {
                $query->where('name', 'LIKE', '%' . $this->search . '%');
            })
            ->paginate(10);

        return view('livewire.types-of-movements.types-of-movements-index', compact('types_of_movements'));
    }
}
