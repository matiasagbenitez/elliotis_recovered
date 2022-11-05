<?php

namespace App\Http\Livewire\ProductTypes;

use App\Models\Unity;
use App\Models\Measure;
use Livewire\Component;
use App\Models\ProductName;

class EditProductType extends Component
{
    public $isOpen = 0;
    public $product_type;

    public $editForm = ['product_name_id' => '', 'measure_id' => '', 'unity_id' => ''];
    public $measures = [], $unities = [], $productNames = [];

    public function mount($product_type)
    {
        $this->product_type = $product_type;
        $this->editForm = [
            'product_name_id' => $product_type->product_name_id,
            'measure_id' => $product_type->measure_id,
            'unity_id' => $product_type->unity_id
        ];
        $this->productNames = ProductName::all();
        $this->measures = Measure::orderBy('favorite', 'desc')->get();
        $this->unities = Unity::all();
    }

    public function editProductType()
    {
        $this->openModal();
        $this->mount($this->product_type);
    }

    public function openModal()
    {
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    public function update()
    {
        try {
            $this->validate([
                'editForm.product_name_id' => 'required|exists:product_names,id',
                'editForm.measure_id' => 'required|exists:measures,id',
                'editForm.unity_id' => 'required|exists:unities,id',
                'editForm.product_name_id' => 'unique:product_types,product_name_id,' . $this->product_type->id . ',id,measure_id,' . $this->editForm['measure_id'] . ',unity_id,' . $this->editForm['unity_id'],
            ]);

            $this->product_type->update([
                'product_name_id' => $this->editForm['product_name_id'],
                'measure_id' => $this->editForm['measure_id'],
                'unity_id' => $this->editForm['unity_id']
            ]);

            $this->reset('editForm');
            $this->closeModal();
            $this->emit('success', '¡El tipo de producto se ha actualizado correctamente!');
            $this->emitTo('product-types.index-product-types', 'refresh');
        } catch (\Exception $e) {
            $this->emit('error', '¡El tipo de producto al que desea actualizar ya existe!');
        }
    }

    public function render()
    {
        return view('livewire.product-types.edit-product-type');
    }
}
