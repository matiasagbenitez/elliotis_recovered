<?php

namespace App\Http\Livewire\Products;

use App\Models\Phase;
use App\Models\Product;
use Livewire\Component;
use App\Models\WoodType;
use App\Models\ProductType;

class EditProduct extends Component
{
    public $product;

    public $types_of_product, $types_of_wood, $phases;

    public $editForm = [
        'name' => '',
        'product_type_id' => '',
        'phase_id' => '',
        'm2' => '',
        'm2_price' => 0,
        'cost' => 0,
        'margin' => 1,
        'selling_price' => 0,
        'real_stock' => '',
        'necessary_stock' => null,
        'minimum_stock' => '',
        'reposition' => '',
        'is_salable' => 0,
        'is_buyable' => 0,
        'wood_type_id' => '',
        'iva_type_id' => 2,
    ];

    protected $validationAttributes = [
        'editForm.name' => 'nombre',
        'editForm.product_type_id' => 'tipo de producto',
        'editForm.phase_id' => 'fase',
        'editForm.m2' => 'm2',
        'editForm.m2_price' => 'precio m2',
        'editForm.cost' => 'costo',
        'editForm.margin' => 'margen',
        'editForm.selling_price' => 'precio de venta',
        'editForm.real_stock' => 'stock real',
        'editForm.minimum_stock' => 'stock mínimo',
        'editForm.reposition' => 'reposición',
        'editForm.wood_type_id' => 'tipo de madera',
        'editForm.iva_type_id' => 'tipo de iva',
        'editForm.is_salable' => 'es vendible',
        'editForm.is_buyable' => 'es comprable',
    ];

    public $stock = [
        'real_stock' => '',
        'minimum_stock' => '',
        'reposition' => '',
    ];

    public function mount(Product $product)
    {
        $this->types_of_product = ProductType::all();
        $this->types_of_wood = WoodType::all();
        $this->phases = Phase::all();
        $this->product = $product;
        $this->fillInputs();
    }

    public function fillInputs()
    {
        $this->editForm['name'] = $this->product->name;
        $this->editForm['product_type_id'] = $this->product->product_type_id;
        $this->editForm['phase_id'] = $this->product->phase_id;
        $this->editForm['m2'] = $this->product->m2 ? $this->product->m2 : 0;
        $this->editForm['m2_price'] = $this->product->m2_price ? $this->product->m2_price : 0;
        $this->editForm['cost'] = $this->product->cost;
        $this->editForm['margin'] = $this->product->margin;
        $this->editForm['selling_price'] = $this->product->selling_price;
        $this->editForm['real_stock'] = $this->product->real_stock;
        $this->editForm['necessary_stock'] = $this->product->necessary_stock;
        $this->editForm['minimum_stock'] = $this->product->minimum_stock;
        $this->editForm['reposition'] = $this->product->reposition;
        $this->editForm['is_salable'] = $this->product->is_salable;
        $this->editForm['is_buyable'] = $this->product->is_buyable;
        $this->editForm['wood_type_id'] = $this->product->wood_type_id;
        $this->editForm['iva_type_id'] = $this->product->iva_type_id;

        $this->updatedEditFormM2();
    }

    // Create a function that executes when $editForm['product_type_id] or $editForm['wood_type_id'] changes
    public function updatedEditFormProductTypeId()
    {
        $wood_type = WoodType::find($this->editForm['wood_type_id']);
        $wood_name = $wood_type ? '(' . strtolower($wood_type->name) . ')' : '';
        $product = ProductType::find($this->editForm['product_type_id']);
        $product_name = $product ? $product->product_name->name . ' ' . $product->measure->name . ' ' . $wood_name : '';
        $product_m2 = $product ? $product->measure->m2 * $product->unity->unities : '';

        $this->editForm['name'] = $product_name;
        $this->editForm['m2'] = $product_m2;

        $this->updatedEditFormM2();
    }

    public function updatedEditFormWoodTypeId()
    {
        $this->updatedEditFormProductTypeId();
    }

    public function updatedEditFormM2()
    {
        if ($this->editForm['m2'] != '' && $this->editForm['m2_price'] != '' && $this->editForm['m2'] != 0) {
            $this->editForm['selling_price'] = '$' .  number_format($this->editForm['m2'] * $this->editForm['m2_price'], 2, ',', '.');
        } elseif ($this->editForm['m2'] == 0 && $this->editForm['m2_price'] != '') {
            $this->editForm['selling_price'] = '$' .  number_format($this->editForm['m2_price'], 2, ',', '.');
        } else {
            $this->editForm['selling_price'] = '';
        }

        $this->updatedEditFormRealStock();
        $this->updatedEditFormMinimumStock();
        $this->updatedEditFormReposition();
    }

    public function updatedEditFormM2Price()
    {
        $this->updatedEditFormM2();
    }

    public function updatedEditFormRealStock()
    {
        if ($this->editForm['real_stock'] > 0 && $this->editForm['m2'] > 0) {
            $this->stock['real_stock'] = $this->editForm['real_stock'] * $this->editForm['m2'] . ' m²';
        } else {
            $this->stock['real_stock'] = '';
        }
    }

    public function updatedEditFormMinimumStock()
    {
        if ($this->editForm['minimum_stock'] > 0 && $this->editForm['m2'] > 0) {
            $this->stock['minimum_stock'] = $this->editForm['minimum_stock'] * $this->editForm['m2'] . ' m²';
        } else {
            $this->stock['minimum_stock'] = '';
        }
    }

    public function updatedEditFormReposition()
    {
        if ($this->editForm['reposition'] > 0 && $this->editForm['m2'] > 0) {
            $this->stock['reposition'] = $this->editForm['reposition'] * $this->editForm['m2'] . ' m²';
        } else {
            $this->stock['reposition'] = '';
        }
    }

    public function save()
    {
        if ($this->editForm['selling_price'] != '' && $this->editForm['selling_price'] != 0 && $this->editForm['m2'] != '' && $this->editForm['m2'] != 0) {
            $this->editForm['selling_price'] = $this->editForm['m2'] * $this->editForm['m2_price'] ?? 0;
            $this->editForm['cost'] = $this->editForm['selling_price'];
            $this->editForm['margin'] = 1;
            $this->editForm['iva_type_id'] = 2;
        } elseif ($this->editForm['m2'] == 0 && $this->editForm['m2_price'] != '') {
            $this->editForm['cost'] = $this->editForm['m2_price'];
            $this->editForm['margin'] = 1;
            $this->editForm['selling_price'] = $this->editForm['m2_price'];
            $this->editForm['m2'] = null;
            $this->editForm['m2_price'] = null;
        } else {
            $this->emit('error', 'Ha ocurrido un error al guardar el producto.');
        }

        // Check composite key
        $product = Product::where('product_type_id', $this->editForm['product_type_id'])
            ->where('wood_type_id', $this->editForm['wood_type_id'])
            ->where('phase_id', $this->editForm['phase_id'])
            ->first();



        if ($product && $product->id != $this->product->id) {
            $this->emit('error', 'El producto que intenta crear ya existe.');
            return;
        }

        $rules = [
            'editForm.name' => 'required',
            'editForm.product_type_id' => 'required|numeric|exists:product_types,id',
            'editForm.phase_id' => 'required|numeric|exists:phases,id',
            'editForm.m2' => 'nullable|numeric|min:0',
            'editForm.m2_price' => 'nullable|numeric|min:1',
            'editForm.cost' => 'required|numeric|min:0',
            'editForm.margin' => 'required|numeric|min:0',
            'editForm.selling_price' => 'required|numeric|min:0',
            'editForm.real_stock' => 'required|numeric|min:1',
            'editForm.minimum_stock' => 'required|numeric|min:1',
            'editForm.reposition' => 'required|numeric|min:0',
            'editForm.wood_type_id' => 'required|numeric|exists:wood_types,id',
            'editForm.iva_type_id' => 'required|numeric|exists:iva_types,id',
            'editForm.is_salable' => 'required|numeric|in:0,1',
            'editForm.is_buyable' => 'required|numeric|in:0,1',
        ];

        $this->validate($rules);

        $product = Product::find($this->product->id);

        try {
            $product->update($this->editForm);
        } catch (\Throwable $th) {
            $this->emit('error', 'Ha ocurrido un error al guardar el producto.');
        }

        $message = '¡El producto ' . $product->name . ' ha sido actualizado exitosamente!';
        session()->flash('flash.banner', $message);

        return redirect()->route('admin.products.index');
    }

    public function render()
    {
        return view('livewire.products.edit-product');
    }
}
