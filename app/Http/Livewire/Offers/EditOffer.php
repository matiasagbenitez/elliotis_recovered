<?php

namespace App\Http\Livewire\Offers;

use App\Models\Hash;
use App\Models\Offer;
use App\Models\Product;
use Livewire\Component;
use App\Models\OfferProduct;
use App\Models\ProductTendering;
use App\View\Components\GuestLayout;

class EditOffer extends Component
{
    public $hash;
    public $offer;
    public $supplier;
    public $tendering;

    protected $listeners = ['delete'];

    public $editForm = [
        'tn_total' => '',
        'tn_price' => '',
        'subtotal' => '',
        'iva' => '',
        'total' => '',
        'delivery_date' => '',
        'observations' => '',
    ];

    // PRODUCTS
    public $orderProducts = [];
    public $allProducts = [];

    public function mount(Hash $hash)
    {
        if (!$hash->is_active || !$hash->offer) {
            // Abort with message
            abort(404, 'Hash no válido');
        }

        $this->hash = $hash;
        $this->offer = $hash->offer;
        $this->tendering = $hash->tendering;
        $this->supplier = $hash->supplier;
        $this->allProducts = Product::where('is_buyable', true)->whereHas('tenderings', function ($query) use ($hash) {
            $query->where('tendering_id', $hash->tendering->id);
        })->orderBy('name')->get();

        $this->orderProducts = OfferProduct::where('offer_id', $this->offer->id)->get()->toArray();
        $tn_price = round($this->offer->total / $this->offer->tn_total, 2);
        $this->editForm = $this->offer->toArray();
        $this->editForm['tn_price'] = $tn_price;

        $this->updatedOrderProducts();
    }

    public function addProduct()
    {
        if (count($this->orderProducts) == count($this->allProducts)) {
            return;
        }

        if (!empty($this->orderProducts[count($this->orderProducts) - 1]['product_id']) || count($this->orderProducts) == 0) {
            $this->orderProducts[] = ['product_id' => '', 'quantity' => 1];
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

    public function updatedEditForm()
    {
        if ($this->editForm['tn_total'] == ''|| $this->editForm['tn_price'] == '') {
            return;
        }

        $total = $this->editForm['tn_total'] * $this->editForm['tn_price'];
        $iva = $total * 0.21;
        $subtotal = $total - $iva;

        $this->editForm['subtotal'] = $subtotal;
        $this->editForm['iva'] = $iva;
        $total = number_format($total, 2, '.', '');
        $this->editForm['total'] = $total;
    }

    public function update()
    {
        $this->validate([
            'editForm.delivery_date' => 'required|date|after_or_equal:' . $this->tendering->end_date,
            'editForm.observations' => 'nullable|string',
            'orderProducts' => 'required|array',
            'orderProducts.*.product_id' => 'required|exists:products,id',
            'orderProducts.*.quantity' => 'required|integer|min:1',
            'editForm.tn_total' => 'required|numeric|min:1',
            'editForm.tn_price' => 'required|numeric|min:1',
            'editForm.subtotal' => 'required|numeric|min:1',
            'editForm.iva' => 'required|numeric|min:1',
            'editForm.total' => 'required|numeric|min:1',
        ]);

        // Verify that quantity is not greater than the quantity of the tendering
        foreach ($this->orderProducts as $product) {
            $productTendering = ProductTendering::where('tendering_id', $this->hash->tendering->id)->where('product_id', $product['product_id'])->first();
            if ($product['quantity'] > $productTendering->quantity) {
                $productName = Product::find($product['product_id'])->name;
                $this->emit('error', 'La cantidad del producto ' . $productName . ' no puede ser mayor a la cantidad solicitada en la licitación');
                return;
            }
        }

        $this->offer->update([
            'subtotal' => $this->editForm['subtotal'],
            'iva' => $this->editForm['iva'],
            'total' => $this->editForm['total'],
            'tn_total' => $this->editForm['tn_total'],
            'delivery_date' => $this->editForm['delivery_date'],
            'observations' => $this->editForm['observations'],
        ]);

        // Detach all products
        $this->offer->products()->detach();

        foreach ($this->orderProducts as $product) {
            $this->offer->products()->attach($product['product_id'], ['quantity' => $product['quantity']]);
        }

        $editHash = $this->offer->hash;

        // Products OK
        $tenderingProductsId = $editHash->tendering->products->pluck('id')->toArray();
        sort($tenderingProductsId);
        $offerProductsId = $editHash->offer->products->pluck('id')->toArray();
        sort($offerProductsId);
        $editHash->offer->products_ok = $tenderingProductsId == $offerProductsId;
        $editHash->offer->save();

        if($tenderingProductsId == $offerProductsId) {
            $this->offer->update([
                'products_ok' => true,
            ]);
        } else {
            $this->offer->update([
                'products_ok' => false,
            ]);
        }

        // Quantities OK
        $hasAllProducts = false;
        foreach($editHash->offer->products as $product) {
            if ($product->pivot->quantity == $editHash->tendering->products->where('id', $product->id)->first()->pivot->quantity) {
                $hasAllProducts = true;
            } else {
                $hasAllProducts = false;
                break;
            }
        }
        $this->offer->quantities_ok = $hasAllProducts;

        $this->offer->save();

        // session()->flash('success', 'Oferta actualizada correctamente.');
        return redirect()->route('offer.updated-successfully', $this->hash->hash);
    }

    public function delete(Offer $offer)
    {
        try {
            $offer->hash->update([
                'is_active' => false,
                'cancelled' => true,
                'cancelled_at' => now(),
            ]);
            $this->emit('success', 'Oferta eliminada correctamente.');
        } catch (\Exception $e) {
            $this->emit('error', 'No se ha podido eliminar la oferta.');
        }

        return redirect()->route('offer.deleted-successfully', $this->hash->hash);
    }

    public function render()
    {
        return view('livewire.offers.edit-offer')->layout(GuestLayout::class);
    }
}
