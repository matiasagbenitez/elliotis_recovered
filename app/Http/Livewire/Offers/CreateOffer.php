<?php

namespace App\Http\Livewire\Offers;

use App\Models\Hash;
use App\Models\Product;
use App\Models\ProductTendering;
use App\Models\Supplier;
use App\Models\Tendering;
use Livewire\Component;
use App\View\Components\GuestLayout;
use Illuminate\Support\Facades\Date;

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
        'tn_total' => 0,
        'tn_price' => 0,
        'subtotal' => 0,
        'iva' => 0,
        'total' => 0,
        'delivery_date' => '',
        'observations' => '',
    ];

    protected $validationAttributes = [
        'createForm.tn_total' => 'total de TN',
        'createForm.tn_price' => 'precio por TN',
        'createForm.subtotal' => 'subtotal',
        'createForm.iva' => 'IVA',
        'createForm.total' => 'total',
        'createForm.delivery_date' => 'fecha de entrega',
        'createForm.observations' => 'observaciones',
    ];

    public function mount(Hash $hash)
    {
        $this->checkHash($hash);

        $this->allProducts = Product::where('is_buyable', true)->whereHas('tenderings', function ($query) use ($hash) {
            $query->where('tendering_id', $hash->tendering->id);
        })->orderBy('name')->get();

        $this->orderProducts = ProductTendering::where('tendering_id', $hash->tendering_id)->get(['product_id', 'quantity'])->toArray();

        $this->supplier = $hash->supplier;
        $this->tender = $hash->tendering;

        $this->fakeData();
    }

    public function fakeData()
    {
        $end_date = Date::parse($this->tender->end_date);
        $end_date->addDays(rand(1, 2))->setTime(rand(7, 18), 30);

        $total_quantity = $this->tender->products->sum('pivot.quantity');

        $this->createForm['tn_total'] = number_format($total_quantity * 0.55, 2, '.', '');
        $this->createForm['tn_price'] = rand(2500, 3000);
        $this->createForm['delivery_date'] = date('Y-m-d\TH:i', strtotime($end_date));
        $this->createForm['observations'] = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.';

        $this->updatedCreateForm();
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

    public function updatedCreateForm()
    {
        if ($this->createForm['tn_total'] == ''|| $this->createForm['tn_price'] == '') {
            return;
        }

        $total = $this->createForm['tn_total'] * $this->createForm['tn_price'];
        $iva = $total * 0.21;
        $subtotal = $total - $iva;

        $this->createForm['subtotal'] = $subtotal;
        $this->createForm['iva'] = $iva;
        $total = number_format($total, 2, '.', '');
        $this->createForm['total'] = $total;
    }

    public function save()
    {
        $this->validate([
            'createForm.delivery_date' => 'required|date|after_or_equal:' . $this->tender->end_date,
            'createForm.observations' => 'nullable|string',
            'orderProducts' => 'required|array',
            'orderProducts.*.product_id' => 'required|exists:products,id',
            'orderProducts.*.quantity' => 'required|integer|min:1',
            'createForm.tn_total' => 'required|numeric|min:1',
            'createForm.tn_price' => 'required|numeric|min:1',
            'createForm.subtotal' => 'required|numeric|min:1',
            'createForm.iva' => 'required|numeric|min:1',
            'createForm.total' => 'required|numeric|min:1',
        ]);

        // Verify that quantity is not greater than the quantity of the tendering
        foreach ($this->orderProducts as $product) {
            $productTendering = ProductTendering::where('tendering_id', $this->tender->id)->where('product_id', $product['product_id'])->first();
            if ($product['quantity'] > $productTendering->quantity) {
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
            'tn_total' => $this->createForm['tn_total'],
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
