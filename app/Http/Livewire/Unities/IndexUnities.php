<?php

namespace App\Http\Livewire\Unities;

use App\Models\Unity;
use Livewire\Component;

class IndexUnities extends Component
{
    protected $listeners = ['delete', 'refresh' => 'render'];

    public function delete(Unity $unity)
    {
        try {
            $unity->delete();
            $this->emit('success', '¡Unidad eliminada con éxito!');
        } catch (\Exception $e) {
            $this->emit('error', 'No se puede eliminar la unidad porque está siendo usada.');
        }
    }

    public function render()
    {
        $unities = Unity::orderBy('updated_at', 'DESC')->get();
        return view('livewire.unities.index-unities', compact('unities'));
    }
}
