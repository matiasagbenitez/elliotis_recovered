<?php

namespace App\Http\Livewire\Products;

use App\Models\PreviousProduct;
use App\Models\Product;
use Livewire\Component;

class CreatePreviousProduct extends Component
{
    public $product, $products;
    public $previous_product_id;

    public function mount(Product $product)
    {
        $this->product = $product;
        if ($product->previousProduct) {
            $this->previous_product_id = $product->previousProduct->id;
        }

        $this->products = Product::where('id', '!=', $product->id)->get();
    }

    public function create()
    {
        if ($this->previous_product_id != '' && $this->previous_product_id != null) {
            $previousProduct = PreviousProduct::updateOrCreate(
                ['product_id' => $this->product->id],
                ['previous_product_id' => $this->previous_product_id]
            );
            $message = '¡Se actualizó exitosamente el producto anterior de ' . $this->product->name . '!';
            session()->flash('flash.banner', $message);
            return redirect()->route('admin.products.show', $this->product);
        } else {
            $this->emit('error', '¡Ocurrió un error al actualizar el producto anterior de ' . $this->product->name . '!');
            return;
        }

    }

    public function render()
    {
        return view('livewire.products.create-previous-product');
    }
}
