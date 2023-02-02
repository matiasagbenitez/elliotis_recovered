<?php

namespace App\Http\Livewire\Tenderings;

use App\Http\Services\TenderingService;
use App\Models\Offer;
use App\Models\Tendering;
use Illuminate\Support\Facades\Date;
use Livewire\Component;
use Termwind\Components\Dd;

class ShowFinishedTendering extends Component
{
    public $tender, $hashes;

    public $bestOffer, $bestOfferStats = [];


    public function mount(Tendering $tendering)
    {
        if (!$tendering->is_finished) {
            abort(404);
        }

        if (!$tendering->bestOffer) {
            TenderingService::init($tendering);
        } else {
            $this->bestOffer = $tendering->bestOffer;
            $this->getBestOfferStats();
        }

        $this->tender = $tendering;
        $this->hashes = $tendering->hashes;

        // Collection of offers from the tendering related with the hashes
        // $this->offers = Offer::whereHas('hash', function ($query) use ($tendering) {
        //     $query->where('tendering_id', $tendering->id);
        // })->get();
    }

    public function getBestOfferStats()
    {
        $supplier = $this->bestOffer->offer->hash->supplier->business_name;
        $products_ok = $this->bestOffer->offer->products_ok ? 'Productos completos y ' : 'Productos incompletos y ';
        $quantities_ok = $this->bestOffer->offer->quantities_ok ? 'cantidades completas.' : 'cantidades incompletas.';
        $price_per_tn = $this->bestOffer->offer->total / $this->bestOffer->offer->tn_total;
        $summary = 'Rollos por un total de ' . $this->bestOffer->offer->tn_total . ' TN a $' . $price_per_tn . ' / TN.';
        $delivery_date = Date::parse($this->bestOffer->offer->delivery_date)->format('d-m-Y H:i') . 'hs';

        $this->bestOfferStats = [
            'id' => $this->bestOffer->id,
            'tendering_id' => $this->bestOffer->tendering_id,
            'hash' => $this->bestOffer->offer->hash->hash,
            'supplier' => $supplier,
            'products_quantities' => $products_ok . $quantities_ok,
            'total' => number_format($this->bestOffer->offer->total, 2, ',', '.'),
            'summary' => $summary,
            'delivery_date' => $delivery_date,
        ];
    }

    public function render()
    {
        return view('livewire.tenderings.show-finished-tendering');
    }
}
