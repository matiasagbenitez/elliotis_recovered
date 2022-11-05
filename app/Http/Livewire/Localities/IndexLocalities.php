<?php

namespace App\Http\Livewire\Localities;

use App\Models\Country;
use Livewire\Component;
use App\Models\Locality;
use Livewire\WithPagination;

class IndexLocalities extends Component
{
    use WithPagination;

    public $search;

    public $listeners = ['delete', 'refresh' => 'render'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function delete(Locality $locality)
    {
        try {
            $locality->delete();
            $this->emit('refresh');
            $this->emit('success', '¡Localidad eliminada con éxito!');
        } catch (\Exception $e) {
            $this->emit('error', 'No puedes eliminar esta localidad porque tiene clientes y/o proveedores asociados.');
        }
    }

    public function render()
    {
        $localities = Locality::where('name', 'LIKE', "%" . $this->search . "%")
                    ->orwhere('postal_code', 'LIKE', '%' . $this->search . '%')
                    ->orderBy('updated_at', 'DESC')->paginate(6);

        return view('livewire.localities.index-localities', compact('localities'));
    }
}
