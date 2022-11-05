<?php

namespace App\Http\Livewire\Countries;

use App\Models\Country;
use Livewire\Component;
use Livewire\WithPagination;

class IndexCountries extends Component
{
    use WithPagination;

    public $search;
    protected $listeners = ['delete', 'refresh' => 'render'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function delete(Country $country)
    {
        try {
            $country->delete();
            $this->emit('refresh');
            $this->emit('success', '¡País eliminado con éxito!');
        } catch (\Exception $e) {
            $this->emit('error', 'No puedes eliminar este país porque tiene provincias asociadas.');
        }
    }

    public function render()
    {
        $countries = Country::where('name', 'LIKE', "%" . $this->search . "%")->orderBy('updated_at', 'DESC')->paginate(6);
        return view('livewire.countries.index-countries', compact('countries'));
    }
}
