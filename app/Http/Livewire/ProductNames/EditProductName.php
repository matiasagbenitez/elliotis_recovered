<?php

namespace App\Http\Livewire\ProductNames;

use Livewire\Component;
use App\Models\ProductName;

class EditProductName extends Component
{
    public $isOpen = 0;

    public $product_name, $product_name_id;

    public $editForm = [
        'name' => '',
        'margin' => '',
    ];

    protected $validationAttributes = [
        'editForm.name' => 'product name',
        'editForm.margin' => 'margin',
    ];

    public function mount(ProductName $product_name)
    {
        $this->product_name = $product_name;
        $this->product_name_id = $product_name->id;
        $this->editForm['name'] = $product_name->name;
        $this->editForm['margin'] = $product_name->margin;
    }

    public function editProductName()
    {
        $this->openModal();
    }

    public function openModal()
    {
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->resetInputFields();
    }

    public function resetInputFields()
    {
        $this->reset('editForm');
        $this->resetErrorBag();
    }

    public function update()
    {
        try {
            $this->validate([
                'editForm.name' => 'required|unique:product_names,name,' . $this->product_name_id,
                'editForm.margin' => 'required|numeric|min:1|max:100',
            ]);

            $this->product_name->update([
                'name' => $this->editForm['name'],
                'margin' => $this->editForm['margin'],
            ]);

            // Update all products margin where product_type->product_name = $this->product_name
            $this->product_name->product_types->each(function ($product_type) {
                $product_type->products->each(function ($product) {
                    $product->update([
                        'margin' => $this->editForm['margin'],
                        'selling_price' => $product->selling_price * $this->editForm['margin'],
                    ]);
                });
            });
        } catch (\Exception $e) {
            $this->emit('error', '¡Error al actualizar el nombre de producto!');
            return;
        }



        $this->closeModal();
        $this->emit('success', '¡Nombre de producto actualizado con éxito!');
        $this->emitTo('product-names.index-product-names', 'refresh');
    }

    public function render()
    {
        return view('livewire.product-names.edit-product-name');
    }
}
