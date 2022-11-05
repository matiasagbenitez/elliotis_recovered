<?php

namespace App\Http\Livewire\ProductTypes;

use Livewire\Component;
use App\Models\ProductType;
use Livewire\WithPagination;

class IndexProductTypes extends Component
{
    use WithPagination;

    public $search;

    public $listeners = ['delete', 'refresh' => 'render'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function delete(ProductType $productType)
    {
        try {
            $productType->delete();
            $this->emit('success', '¡Tipo de producto eliminado con éxito!');
            $this->emit('refresh');
        } catch (\Throwable $th) {
            $this->emit('error', 'Error al eliminar el tipo de producto.');
        }
    }


    public function render()
    {
        $product_types = ProductType::orderBy('updated_at', 'DESC')->paginate(6);

        return view('livewire.product-types.index-product-types', compact('product_types'));
    }
}
