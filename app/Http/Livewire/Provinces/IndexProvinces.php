<?php

namespace App\Http\Livewire\Provinces;

use App\Models\Country;
use Livewire\Component;
use App\Models\Province;
use Livewire\WithPagination;

class IndexProvinces extends Component
{
    use WithPagination;

    public $search;
    protected $listeners = ['refresh' => 'render', 'delete'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function delete(Province $province)
    {
        try {
            $province->delete();
            $this->emit('refresh');
            $this->emit('success', '¡Provincia eliminada con éxito!');
        } catch (\Exception $e) {
            $this->emit('error', 'No puedes eliminar esta provincia porque tiene localidades asociadas.');
        }
    }

    public function render()
    {
        $provinces = Province::where('name', 'LIKE', "%" . $this->search . "%")->orderBy('updated_at', 'DESC')->paginate(6);
        return view('livewire.provinces.index-provinces', compact('provinces'));
    }
}
