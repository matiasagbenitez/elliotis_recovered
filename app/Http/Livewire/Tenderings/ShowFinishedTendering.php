<?php

namespace App\Http\Livewire\Tenderings;

use App\Models\Offer;
use Livewire\Component;
use App\Models\Tendering;
use Termwind\Components\Dd;
use App\Models\PurchaseOrder;
use Illuminate\Support\Facades\Date;
use App\Http\Services\TenderingService;

class ShowFinishedTendering extends Component
{
    public $tendering, $hashes;

    public $bestOffer, $bestOfferStats = [], $bestOfferHasOrderAssociated, $allOffersStats = [];
    protected $listeners = ['createPurchaseOrder'];

    public function mount(Tendering $tendering)
    {
        if (!$tendering->is_finished) {
            abort(404);
        }

        $this->bestOffer = $tendering->bestOffer;
        $this->bestOfferHasOrderAssociated = $this->bestOffer->has_purchase_order;
        $this->bestOfferStats = $this->getBestOfferStats();
        $this->allOffersStats = $this->getAllOffersStats();

        $this->tendering = $tendering;
    }

    public function getBestOfferStats()
    {
        $supplier = $this->bestOffer->offer->hash->supplier->business_name;
        $products_ok = $this->bestOffer->offer->products_ok ? 'Productos completos y ' : 'Productos incompletos y ';
        $quantities_ok = $this->bestOffer->offer->quantities_ok ? 'cantidades completas.' : 'cantidades incompletas.';
        $price_per_tn = $this->bestOffer->offer->total / $this->bestOffer->offer->tn_total;
        $summary = 'Rollos por un total de ' . $this->bestOffer->offer->tn_total . ' TN a $' . $price_per_tn . ' / TN.';
        $delivery_date = Date::parse($this->bestOffer->offer->delivery_date)->format('d-m-Y H:i') . 'hs';

        $bestOfferStats = [
            'offer_id' => $this->bestOffer->offer_id,
            'tendering_id' => $this->bestOffer->tendering_id,
            'hash' => $this->bestOffer->offer->hash->hash,
            'supplier' => $supplier,
            'products_quantities' => $products_ok . $quantities_ok,
            'total' => number_format($this->bestOffer->offer->total, 2, ',', '.'),
            'summary' => $summary,
            'delivery_date' => $delivery_date,
        ];

        return $bestOfferStats;
    }

    public function getAllOffersStats()
    {
        $offers = Offer::whereHas('hash', function ($query) {
            $query->where('tendering_id', $this->tendering->id)->where('cancelled', false);
        })->get();

        $offersStats = [];

        foreach ($offers as $offer) {
            $supplier = $offer->hash->supplier->business_name;
            $answered_at = Date::parse($offer->hash->answered_at)->format('d-m-Y H:i') . 'hs';
            $products_ok = $offer->products_ok ? 'Productos completos y ' : 'Productos incompletos y ';
            $quantities_ok = $offer->quantities_ok ? 'cantidades completas.' : 'cantidades incompletas.';
            $delivery_date = Date::parse($offer->delivery_date)->format('d-m-Y H:i') . 'hs';

            $offersStats[] = [
                'offer_id' => $offer->id,
                'supplier' => $supplier,
                'answered_at' => $answered_at,
                'hash' => $offer->hash->hash,
                'products_quantities' => $products_ok . $quantities_ok,
                'total' => '$' . number_format($offer->total, 2, ',', '.'),
                'total_tn' => $offer->tn_total . ' TN',
            ];
        }

        return $offersStats;
    }

    public function createPurchaseOrder($offerId)
    {
        try {
            $offer = Offer::find($offerId);

            $createForm = [
                'user_id' => auth()->user()->id,
                'supplier_id' => $offer->hash->supplier_id,
                'registration_date' => now(),
                'subtotal' => $offer->subtotal,
                'iva' => $offer->iva,
                'total' => $offer->total,
                'total_weight' => $offer->tn_total,
                'observations' => $offer->observations,
                'type_of_purchase' => 2,
            ];

            $purchaseOrder = PurchaseOrder::create($createForm);

            $tn_price = $offer->total / $offer->tn_total;
            $total_products = $offer->products->sum('pivot.quantity');

            foreach ($offer->products as $product) {
                $tn_product = ($product->pivot->quantity * $offer->tn_total) / $total_products;
                $purchaseOrder->products()->attach($product->id, [
                    'quantity' => $product->pivot->quantity,
                    'tn_total' => $tn_product,
                    'tn_price' => $tn_price / 1.21,
                    'subtotal' => $tn_product * $tn_price,
                ]);
            }

            $this->bestOffer->update(['has_purchase_order' => true]);
            $this->bestOfferHasOrderAssociated = true;
            $this->emit('success', 'Orden de compra creada correctamente.');
            $this->render();

        } catch (\Throwable $th) {
            $this->emit('error', 'Error al crear la orden de compra.');
        }
    }

    public function render()
    {
        return view('livewire.tenderings.show-finished-tendering');
    }
}
