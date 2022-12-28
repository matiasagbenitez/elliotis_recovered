<?php

namespace App\Http\Livewire\Phases;

use App\Models\Phase;
use Livewire\Component;
use Livewire\WithPagination;

class IndexPhases extends Component
{
    use WithPagination;
    public $search;

    protected $listeners = ['refresh' => 'render', 'delete'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function delete(Phase $phase)
    {
        try {
            $phase->delete();
            $this->emit('refresh');
            $this->emit('success', '¡La fase se eliminó correctamente!');
        } catch (\Throwable $th) {
            $this->emit('error', 'No se pudo eliminar la fase.');
        }
    }

    public function render()
    {
        $phases = Phase::where('name', 'LIKE', "%" . $this->search . "%")
            ->paginate(6);
        return view('livewire.phases.index-phases', compact('phases'));
    }
}
