<?php

namespace App\Http\Livewire\Products;

use App\Models\Product;
use App\Models\ProductSale;
use App\Models\Sublot;
use Livewire\Component;

class AddProductsComponent extends Component
{
    public $allSublots = [];
    public $type_of_purchase = 2;

    public $allSublotsFormated = [];

    public $orderProducts = [];
    public $totalWeight = 0;

    public $createForm = [
        'subtotal' => 0,
        'iva' => 0,
        'total' => 0
    ];

    public $weightForm = [
        'totalWeight' => 0,
        'price' => 0,
        'subtotal' => 0,
    ];

    public function mount()
    {
        // Get sublots where available is true and where sublot->product is_salable is true
        $this->allSublots = Sublot::where('available', true)->where('area_id', 8)->whereHas('product', function ($query) {
            $query->where('is_salable', true);
        })->get();

        // dd($this->allSublots);

        foreach ($this->allSublots as $sublot) {
            $this->allSublotsFormated[] = [
                'id' => $sublot->id,
                'text' => '[' . $sublot->actual_quantity . '] ' . $sublot->product->name . ' - Sublote: ' . $sublot->code . ' - Lote: ' . $sublot->lot->code
            ];
        }

        // dd($this->allSublotsFormated);

        // $this->allSublots = Product::where('is_salable', true)->get();

        $this->orderProducts = [
            ['product_id' => '', 'unities' => 1, 'quantity' => 0, 'price_quantity' => 0, 'subtotal' => 0],
        ];
    }

    public function updatedTypeOfPurchase()
    {
        if ($this->type_of_purchase == 1) {
            $this->orderProducts = [
                ['product_id' => '', 'unities' => 1, 'quantity' => 0, 'price_quantity' => 0, 'subtotal' => 0],
            ];
        } elseif ($this->type_of_purchase == 2) {
            $this->orderProducts = [
                ['product_id' => '', 'unities' => 1],
            ];
        }
    }

    public function addProduct()
    {
        if (count($this->orderProducts) == count($this->allSublots)) {
            return;
        }

        if (!empty($this->orderProducts[count($this->orderProducts) - 1]['product_id'])) {
            $this->orderProducts[] = ['product_id' => '', 'unities' => 1, 'quantity' => 0, 'price_quantity' => 0, 'subtotal' => 0];
        }
    }

    public function isProductInOrder($productId)
    {
        foreach ($this->orderProducts as $orderProduct) {
            if ($orderProduct['product_id'] == $productId) {
                return true;
            }
        }

        return false;
    }

    public function removeProduct($index)
    {
        unset($this->orderProducts[$index]);
        $this->orderProducts = array_values($this->orderProducts);
    }

    public function showProducts()
    {
        if ($this->type_of_purchase == 1) {
            dd($this->orderProducts, $this->createForm, $this->totalWeight);
        } else {
            dd($this->orderProducts, $this->weightForm, $this->createForm);
        }
    }

    public function updatedOrderProducts()
    {

        $this->validate([
            'orderProducts.*.product_id' => 'required',
            'orderProducts.*.unities' => 'required|numeric|min:1',
            'orderProducts.*.quantity' => 'required|numeric|min:1',
            'orderProducts.*.price_quantity' => 'required|numeric|min:1',
        ]);

        foreach ($this->orderProducts as $key => $orderProduct) {
            $this->orderProducts[$key]['subtotal'] = number_format($orderProduct['quantity'] * $orderProduct['price_quantity'], 2, '.', '');
        }

        if ($this->type_of_purchase == 1) {

            $this->totalWeight = collect($this->orderProducts)->sum('quantity');

            // Complete createform
            $this->createForm['subtotal'] = number_format(collect($this->orderProducts)->sum('subtotal'), 2, '.', '');
            $this->createForm['iva'] = number_format($this->createForm['subtotal'] * 0.21, 2, '.', '');
            $this->createForm['total'] = number_format($this->createForm['subtotal'] + $this->createForm['iva'], 2, '.', '');

        } else {



        }
    }

    public function updatedWeightForm()
    {
        $this->weightForm['subtotal'] = number_format($this->weightForm['totalWeight'] * $this->weightForm['price'], 2, '.', '');
        $this->createForm['iva'] = number_format($this->weightForm['subtotal'] * 0.21, 2, '.', '');
            $this->createForm['total'] = number_format($this->weightForm['subtotal'] + $this->createForm['iva'], 2, '.', '');

    }

    public function render()
    {
        return view('livewire.products.add-products-component');
    }
}
