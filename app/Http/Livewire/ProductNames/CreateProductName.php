<?php

namespace App\Http\Livewire\ProductNames;

use Livewire\Component;
use App\Models\ProductName;

class CreateProductName extends Component
{
    public $isOpen = 0;

    public $createForm = ['name' => '', 'margin' => ''];

    protected $listeners = ['refresh' => 'render'];

    protected $rules = [
        'createForm.name' => 'required|string|max:255|unique:product_names,name',
        'createForm.margin' => 'required|numeric|min:1|max:100',
    ];

    protected $validationAttributes = [
        'createForm.name' => 'name',
        'createForm.margin' => 'margin',
    ];

    public function createProductName()
    {
        $this->resetInputFields();
        $this->openModal();
    }

    public function openModal()
    {
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    private function resetInputFields()
    {
        $this->resetErrorBag();
        $this->createForm = ['name' => ''];
        $this->createForm = ['margin' => ''];
    }

    public function save()
    {
        try {
            $this->validate();

            $product_name = ProductName::create($this->createForm);

            // Update all products margin where product_type->product_name = $this->product_name
            $product_name->product_types->each(function ($product_type) {
                $product_type->products->each(function ($product) {
                    $product->update([
                        'margin' => $this->editForm['margin'],
                        'selling_price' => $product->selling_price * $this->editForm['margin'],
                    ]);
                });
            });

            $this->emit('success', '¡Nombre de producto creado con éxito!');
            $this->closeModal();
            $this->resetInputFields();
            $this->emit('refresh');
        } catch (\Exception $e) {
            $this->emit('error', '¡Error al crear el nombre de producto!');
        }
    }

    public function render()
    {
        return view('livewire.product-names.create-product-name');
    }
}
