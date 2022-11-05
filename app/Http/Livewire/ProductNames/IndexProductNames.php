<?php

namespace App\Http\Livewire\ProductNames;

use Livewire\Component;
use App\Models\ProductName;
use Livewire\WithPagination;

class IndexProductNames extends Component
{
    use WithPagination;
    public $search;
    protected $listeners = ['refresh' => 'render', 'delete'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function delete(ProductName $product_name)
    {
        try {
            $product_name->delete();
            $this->emit('refresh');
            $this->emit('success', '¡El nombre de producto se eliminó correctamente!');
        } catch (\Throwable $th) {
            $this->emit('error', 'El nombre de producto no se pudo eliminar.');
        }
    }

    public function render()
    {
        $product_names = ProductName::where('name', 'like', '%' . $this->search . '%')->paginate(6);
        return view('livewire.product-names.index-product-names', compact('product_names'));
    }
}
