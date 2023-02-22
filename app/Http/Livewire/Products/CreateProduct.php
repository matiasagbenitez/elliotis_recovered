<?php

namespace App\Http\Livewire\Products;

use App\Models\Phase;
use App\Models\Product;
use Livewire\Component;
use App\Models\WoodType;
use App\Models\ProductType;

class CreateProduct extends Component
{
    public $types_of_product, $types_of_wood, $phases;

    public $createForm = [
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

    protected $rules = [
        'createForm.name' => 'required',
        'createForm.product_type_id' => 'required|numeric|exists:product_types,id',
        'createForm.phase_id' => 'required|numeric|exists:phases,id',
        'createForm.m2' => 'nullable|numeric|min:0',
        'createForm.m2_price' => 'nullable|numeric|min:1',
        'createForm.cost' => 'required|numeric|min:0',
        'createForm.margin' => 'required|numeric|min:0',
        'createForm.selling_price' => 'required|numeric|min:0',
        'createForm.real_stock' => 'required|numeric|min:1',
        'createForm.minimum_stock' => 'required|numeric|min:1',
        'createForm.reposition' => 'required|numeric|min:1',
        'createForm.wood_type_id' => 'required|numeric|exists:wood_types,id',
        'createForm.iva_type_id' => 'required|numeric|exists:iva_types,id',
        'createForm.is_salable' => 'required|numeric|in:0,1',
        'createForm.is_buyable' => 'required|numeric|in:0,1',
    ];

    protected $validationAttributes = [
        'createForm.name' => 'nombre',
        'createForm.product_type_id' => 'tipo de producto',
        'createForm.phase_id' => 'fase',
        'createForm.m2' => 'm2',
        'createForm.m2_price' => 'precio m2',
        'createForm.cost' => 'costo',
        'createForm.margin' => 'margen',
        'createForm.selling_price' => 'precio de venta',
        'createForm.real_stock' => 'stock real',
        'createForm.minimum_stock' => 'stock mínimo',
        'createForm.reposition' => 'reposición',
        'createForm.wood_type_id' => 'tipo de madera',
        'createForm.iva_type_id' => 'tipo de iva',
        'createForm.is_salable' => 'es salable',
        'createForm.is_buyable' => 'es comprable',
    ];

    public $stock = [
        'real_stock' => '',
        'minimum_stock' => '',
        'reposition' => ''
    ];

    public function mount()
    {
        $this->types_of_product = ProductType::all();
        $this->types_of_wood = WoodType::all();
        $this->phases = Phase::all();
    }

    // Create a function that executes when $createForm['product_type_id] or $createForm['wood_type_id'] changes
    public function updatedCreateFormProductTypeId()
    {
        $wood_type = WoodType::find($this->createForm['wood_type_id']);
        $wood_name = $wood_type ? '(' . strtolower($wood_type->name) . ')' : '';
        $product = ProductType::find($this->createForm['product_type_id']);
        $product_name = $product ? $product->product_name->name . ' ' . $product->measure->name . ' ' . $wood_name : '';
        $product_m2 = $product ? $product->measure->m2 * $product->unity->unities : '';

        $this->createForm['name'] = $product_name;
        $this->createForm['m2'] = $product_m2;

        $this->updatedCreateFormM2();
    }

    public function updatedCreateFormWoodTypeId()
    {
        $this->updatedCreateFormProductTypeId();
    }

    public function updatedCreateFormM2()
    {
        if ($this->createForm['m2'] != '' && $this->createForm['m2_price'] != '' && $this->createForm['m2'] != 0) {
            $this->createForm['selling_price'] = '$' .  number_format($this->createForm['m2'] * $this->createForm['m2_price'], 2, ',', '.');
        } elseif ($this->createForm['m2'] == 0 && $this->createForm['m2_price'] != '') {
            $this->createForm['selling_price'] = '$' .  number_format($this->createForm['m2_price'], 2, ',', '.');
        } else {
            $this->createForm['selling_price'] = '';
        }

        $this->updatedCreateFormRealStock();
        $this->updatedCreateFormMinimumStock();
        $this->updatedCreateFormReposition();
    }

    public function updatedCreateFormM2Price()
    {
        $this->updatedCreateFormM2();
    }

    public function updatedCreateFormRealStock()
    {
        if ($this->createForm['real_stock'] > 0 && $this->createForm['m2'] > 0) {
            $this->stock['real_stock'] = $this->createForm['real_stock'] * $this->createForm['m2'] . ' m²';
        } else {
            $this->stock['real_stock'] = '';
        }
    }

    public function updatedCreateFormMinimumStock()
    {
        if ($this->createForm['minimum_stock'] > 0 && $this->createForm['m2'] > 0) {
            $this->stock['minimum_stock'] = $this->createForm['minimum_stock'] * $this->createForm['m2'] . ' m²';
        } else {
            $this->stock['minimum_stock'] = '';
        }
    }

    public function updatedCreateFormReposition()
    {
        if ($this->createForm['reposition'] > 0 && $this->createForm['m2'] > 0) {
            $this->stock['reposition'] = $this->createForm['reposition'] * $this->createForm['m2'] . ' m²';
        } else {
            $this->stock['reposition'] = '';
        }
    }

    public function save()
    {
            if ($this->createForm['selling_price'] != '' && $this->createForm['selling_price'] != 0 && $this->createForm['m2'] != '' && $this->createForm['m2'] != 0) {
                $this->createForm['selling_price'] = $this->createForm['m2'] * $this->createForm['m2_price'] ?? 0;
                $this->createForm['cost'] = $this->createForm['selling_price'];
                $this->createForm['margin'] = 1;
                $this->createForm['iva_type_id'] = 2;
            } elseif ($this->createForm['m2'] == 0 && $this->createForm['m2_price'] != '') {
                $this->createForm['cost'] = $this->createForm['m2_price'];
                $this->createForm['margin'] = 1;
                $this->createForm['selling_price'] = $this->createForm['m2_price'];
                $this->createForm['m2'] = null;
                $this->createForm['m2_price'] = null;
            } else {
                $this->emit('error', 'Ha ocurrido un error al guardar el producto.');
            }

            // Check composite key
            $product = Product::where('product_type_id', $this->createForm['product_type_id'])
                ->where('wood_type_id', $this->createForm['wood_type_id'])
                ->where('phase_id', $this->createForm['phase_id'])
                ->first();
                $this->validate();

            if ($product) {
                $this->emit('error', 'El producto que intenta crear ya existe.');
                return;
            }


            $product = Product::create($this->createForm);

            $message = '¡El producto ' . $product->name . ' ha sido creado exitosamente!';
            session()->flash('flash.banner', $message);

            return redirect()->route('admin.products.index');

    }


    public function render()
    {
        return view('livewire.products.create-product');
    }
}
