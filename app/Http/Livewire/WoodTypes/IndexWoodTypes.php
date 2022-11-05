<?php

namespace App\Http\Livewire\WoodTypes;

use Livewire\Component;
use App\Models\WoodType;
use Livewire\WithPagination;

class IndexWoodTypes extends Component
{
    use WithPagination;

    public $search;

    protected $listeners = ['refresh' => 'render', 'delete'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function delete(WoodType $wood_type)
    {
        try {
            $wood_type->delete();
            $this->emit('success', '¡El tipo de madera se eliminó con éxito!');
            $this->emit('refresh');
        } catch (\Exception $e) {
            $this->emit('error', 'No se pudo eliminar el tipo de madera.');
        }
    }

    public function render()
    {
        $wood_types = WoodType::where('name', 'like', '%' . $this->search . '%')
            ->orderBy('name')
            ->paginate(10);
        return view('livewire.wood-types.index-wood-types', compact('wood_types'));
    }
}
