<?php

namespace App\Http\Livewire\Areas;

use App\Models\Area;
use Livewire\Component;
use Livewire\WithPagination;

class IndexAreas extends Component
{
    use WithPagination;
    public $search;

    protected $listeners = ['refresh' => 'render', 'delete'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function delete(Area $area)
    {
        try {
            $area->delete();
            $this->emit('refresh');
            $this->emit('success', '¡El área se eliminó correctamente!');
        } catch (\Throwable $th) {
            $this->emit('error', 'No se pudo eliminar el área.');
        }
    }

    public function render()
    {
        $areas = Area::where('name', 'LIKE', "%" . $this->search . "%")
            ->orderBy('updated_at', 'DESC')
            ->paginate(6);
        return view('livewire.areas.index-areas', compact('areas'));
    }
}
