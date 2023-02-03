<?php

namespace App\Http\Livewire\Products;

use App\Models\FollowingProduct;
use App\Models\Product;
use Livewire\Component;

class CreateFollowingProducts extends Component
{
    public $product, $products = [], $selected = [];

    public function mount(Product $product)
    {
        $this->product = $product;
        $products = Product::where('id', '!=', $product->id)->get();

        foreach ($products as $item) {
            $this->products[] = [
                'id' => $item->id,
                'name' => $item->name,
                'exists' => $this->product->followingProducts->contains($item) ? true : false,
            ];
        }
    }

    public function save()
    {
        try {
            if ($this->selected == []) {
                $this->emit('error', '¡Ocurrió un error al actualizar los productos siguientes de ' . $this->product->name . '!');
                return;
            }
            $this->product->followingProducts()->sync($this->selected);
            $message = '¡Se actualizaron exitosamente los productos siguientes de ' . $this->product->name . '!';
            session()->flash('flash.banner', $message);
            return redirect()->route('admin.products.show', $this->product);
        } catch (\Throwable $th) {
            dd($th);
            $this->emit('error', '¡Ocurrió un error al actualizar los productos siguientes de ' . $this->product->name . '!');
        }

    }

    public function render()
    {
        return view('livewire.products.create-following-products');
    }
}
