<?php

namespace App\Http\Livewire\ProductTypes;

use App\Models\Unity;
use App\Models\Measure;
use App\Models\ProductName;
use Livewire\Component;
use App\Models\ProductType;

class CreateProductType extends Component
{
    public $isOpen = 0;
    public $createForm = ['product_name_id' => '', 'measure_id' => '', 'unity_id' => ''];

    public $measures = [], $unities = [], $productNames = [];

    protected $validationAttributes = [
        'createForm.product_name_id' => 'name',
        'createForm.measure_id' => 'measure',
        'createForm.unity_id' => 'unity'
    ];

    public function createProductType()
    {
        $this->resetInputFields();
        $this->openModal();
        $this->productNames = ProductName::all();
        $this->measures = Measure::orderBy('favorite', 'desc')->get();
        $this->unities = Unity::all();
    }

    public function openModal()
    {
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    public function resetInputFields()
    {
        $this->createForm = ['product_name_id' => '', 'measure_id' => '', 'unity_id' => ''];
        $this->resetErrorBag();
    }

    public function save()
    {
        try {
            $this->validate([
                'createForm.product_name_id' => 'required|exists:product_names,id',
                'createForm.measure_id' => 'required|exists:measures,id',
                'createForm.unity_id' => 'required|exists:unities,id'
            ]);

            ProductType::create([
                'product_name_id' => $this->createForm['product_name_id'],
                'measure_id' => $this->createForm['measure_id'],
                'unity_id' => $this->createForm['unity_id']
            ]);

            $this->reset('createForm');
            $this->closeModal();
            $this->emit('success', '¡El tipo de producto se ha creado con éxito!');
            $this->emitTo('product-types.index-product-types', 'refresh');
        } catch (\Throwable $th) {
            $this->emit('error', '¡El tipo de producto que intenta crear ya existe!');
        }
    }

    public function render()
    {
        return view('livewire.product-types.create-product-type');
    }
}
