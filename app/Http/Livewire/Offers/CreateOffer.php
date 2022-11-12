<?php

namespace App\Http\Livewire\Offers;

use App\Models\Hash;
use App\Models\Product;
use App\Models\ProductTendering;
use App\Models\Supplier;
use App\Models\Tendering;
use Livewire\Component;
use App\View\Components\GuestLayout;

class CreateOffer extends Component
{
    // PRODUCTS
    public $orderProducts = [];
    public $allProducts = [];
    public $hash;
    public $supplier;
    public $tender;

    // CREATE FORM
    public $createForm = [
        'subtotal' => 0,
        'iva' => 0,
        'total' => 0,
        'delivery_date' => '',
        'observations' => '',
    ];

    public function mount(Hash $hash)
    {
        $this->checkHash($hash);
        // $this->allProducts = Product::where('is_buyable', true)->orderBy('name')->get();

        // $this->allProducts is filled with the products related to the tendering
        $this->allProducts = Product::where('is_buyable', true)->whereHas('tenderings', function ($query) use ($hash) {
            $query->where('tendering_id', $hash->tendering->id);
        })->orderBy('name')->get();

        // $this->orderProducts is filled with product_id, quantity and price of the products from the tendering associated with the hash
        $this->orderProducts = ProductTendering::where('tendering_id', $hash->tendering_id)->get(['product_id', 'quantity'])->toArray();

        // Fill the price of each product with 0
        foreach ($this->orderProducts as $key => $product) {
            $this->orderProducts[$key]['price'] = 0;
        }

        $this->supplier = $hash->supplier;
        $this->tender = $hash->tendering;
    }

    public function checkHash(Hash $hash)
    {
        $this->hash = $hash->hash;

        if (!$hash->seen){
            $hash->update([
                'seen' => true,
                'seen_at' => now(),
            ]);
        }

        if ($hash->answered || !$hash->is_active){
            abort(404);
        }
    }

    public function addProduct()
    {
        if (count($this->orderProducts) == count($this->allProducts)) {
            return;
        }

        if (!empty($this->orderProducts[count($this->orderProducts) - 1]['product_id']) || count($this->orderProducts) == 0) {
            $this->orderProducts[] = ['product_id' => '', 'quantity' => 1, 'price' => 0, 'subtotal' => '0'];
        }
    }

    public function isProductInOrder($productId)
    {
        foreach ($this->orderProducts as $product) {
            if ($product['product_id'] == $productId) {
                return true;
            }
        }
        return false;
    }

    public function removeProduct($index)
    {
        unset($this->orderProducts[$index]);
        $this->orderProducts = array_values($this->orderProducts);
        $this->updatedOrderProducts();
    }

    public function updatedOrderProducts()
    {
        $subtotal = 0;

        foreach ($this->orderProducts as $index => $product) {
            $this->orderProducts[$index]['subtotal'] = number_format($product['quantity'] * $product['price'], 2, '.', '');
            $subtotal += $this->orderProducts[$index]['subtotal'];
        }

        $iva = $subtotal * 0.21;
        $total = $subtotal + $iva;

        $this->createForm['subtotal'] = number_format($subtotal, 2, '.', '');
        $this->createForm['iva'] = number_format($iva, 2, '.', '');
        $this->createForm['total'] = number_format($total, 2, '.', '');
    }

    public function save()
    {
        $this->validate([
            'createForm.delivery_date' => 'required|date|after_or_equal:' . $this->tender->end_date,
            'createForm.observations' => 'nullable|string',
            'orderProducts' => 'required|array',
            'orderProducts.*.product_id' => 'required|exists:products,id',
            'orderProducts.*.quantity' => 'required|integer|min:1',
            'orderProducts.*.price' => 'required|numeric|min:0',
        ]);

        // Verify that quantity is not greater than the quantity of the tendering
        foreach ($this->orderProducts as $product) {
            $productTendering = ProductTendering::where('tendering_id', $this->tender->id)->where('product_id', $product['product_id'])->first();
            if ($product['quantity'] > $productTendering->quantity) {
                // Get product name
                $productName = Product::find($product['product_id'])->name;
                $this->emit('error', 'La cantidad del producto ' . $productName . ' no puede ser mayor a la cantidad solicitada en la licitación');
                return;
            }
        }

        // Find hash or fail
        $hash = Hash::where('hash', $this->hash)->firstOrFail();

        $hash->offer()->create([
            'subtotal' => $this->createForm['subtotal'],
            'iva' => $this->createForm['iva'],
            'total' => $this->createForm['total'],
            'delivery_date' => $this->createForm['delivery_date'],
            'observations' => $this->createForm['observations'],
        ])->products()->attach($this->orderProducts);

        $hash->update([
            'answered' => true,
            'answered_at' => now()
        ]);

        // Products OK
        $tenderingProductsId = $hash->tendering->products->pluck('id')->toArray();
        sort($tenderingProductsId);
        $offerProductsId = $hash->offer->products->pluck('id')->toArray();
        sort($offerProductsId);
        $hash->offer->products_ok = $tenderingProductsId == $offerProductsId;

        // Quantities OK
        $hasAllProducts = false;
        foreach($hash->offer->products as $product) {
            if ($product->pivot->quantity == $hash->tendering->products->where('id', $product->id)->first()->pivot->quantity) {
                $hasAllProducts = true;
            } else {
                $hasAllProducts = false;
                break;
            }
        }
        $hash->offer->quantities_ok = $hasAllProducts;

        $hash->offer->save();

        return redirect()->route('offer.sent-successfully', $hash->hash);
    }

    public function render()
    {
        return view('livewire.offers.create-offer')->layout(GuestLayout::class);
    }
}
