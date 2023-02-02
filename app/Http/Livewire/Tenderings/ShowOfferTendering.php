<?php

namespace App\Http\Livewire\Tenderings;

use App\Models\Hash;
use Livewire\Component;
use App\Models\Tendering;
use Illuminate\Support\Facades\Date;

class ShowOfferTendering extends Component
{
    public $tender;
    public $hash;
    public $offer;
    public $supplier;
    public $stats = [];
    public $products = [];

    public function mount(Tendering $tendering, Hash $hash)
    {
        $this->tender = $tendering;
        $this->hash = $hash;
        $this->offer = $hash->offer;
        $this->supplier = $hash->supplier;

        // If offer is null redirect to 404
        if (!$this->offer) {
            abort(404);
        }

        $this->stats = [
            'supplier_name' => $this->offer->hash->supplier->business_name,
            'offer_seen_at' => $this->offer->hash->seen_at ? Date::parse($this->offer->hash->seen_at)->format('d-m-Y H:i') . ' hs' : 'No ha visto la oferta',
            'offer_answered_at' => $this->offer->hash->answered_at ? Date::parse($this->offer->hash->answered_at)->format('d-m-Y H:i') . ' hs' : 'No ha respondido la oferta',
            'last_updated_at' => $this->offer->hash->answered_at ? Date::parse($this->offer->hash->updated_at)->format('d-m-Y H:i') . ' hs' : Date::parse($this->offer->hash->answered_at)->format('d-m-Y H:i') . ' hs',
            'hash' => $this->offer->hash->hash,
            'tn_total' => number_format($this->offer->tn_total, 2, ',', '.') . ' TN',
            'tn_price' => '$' . number_format($this->offer->total / $this->offer->tn_total, 2, ',', '.'),
            'subtotal' => '$' . number_format($this->offer->subtotal, 2, ',', '.'),
            'iva' => '$' . number_format($this->offer->iva, 2, ',', '.'),
            'total' => '$' . number_format($this->offer->total, 2, ',', '.'),
            'total_products' => $this->offer->products->sum('pivot.quantity'),
            'observations' => $this->offer->observations ?? 'No hay observaciones',
        ];

        foreach ($this->tender->products as $product) {

            $product_name = $product->name;
            $quantity_required = $product->pivot->quantity;
            $is_offered = $this->offer->products->contains($product->id);
            $quantity_offered = $this->offer->products->where('id', $product->id)->first()->pivot->quantity ?? 0;

            $this->products[] = [
                'name' => $product_name,
                'quantity_required' => $quantity_required,
                'is_offered' => $is_offered ? 'Si' : 'No',
                'quantity_offered' => $quantity_offered,
            ];
        }
    }

    public function render()
    {
        return view('livewire.tenderings.show-offer-tendering');
    }
}
