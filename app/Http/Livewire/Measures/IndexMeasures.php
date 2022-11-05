<?php

namespace App\Http\Livewire\Measures;

use App\Models\Measure;
use Livewire\Component;
use Livewire\WithPagination;

class IndexMeasures extends Component
{
    use WithPagination;

    public $search;

    protected $listeners = ['delete', 'refresh' => 'render'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function delete(Measure $measure)
    {
        try {
            $measure->delete();
            $this->emit('refresh');
            $this->emit('success', '¡Medida eliminada con éxito!');
        } catch (\Exception $e) {
            $this->emit('error', 'No se puede eliminar la medida porque está siendo usada.');
        }
    }

    public function render()
    {
        $measures = Measure::where('name', 'like', '%' . $this->search . '%')
            ->orderBy('favorite', 'desc')->orderBy('updated_at', 'desc')
            ->paginate(10);
        return view('livewire.measures.index-measures', compact('measures'));
    }
}
