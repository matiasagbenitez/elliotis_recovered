<?php

namespace App\Http\Livewire\Tenderings;

use App\Models\Tendering;
use Livewire\Component;

class ShowFinishedTendering extends Component
{
    public $tender, $hashes = [];

    public $requestedSuppliers, $seenRequests, $answeredRequests, $cancelledOffers;
    public $title = 'Solicitudes enviadas', $suppliers;

    public function mount(Tendering $tendering)
    {
        if ($tendering->is_finished) {
            $this->tender = $tendering;
            $this->hashes = $tendering->hashes;
            $this->offers = $tendering->offers;

            $this->requestedSuppliers = $tendering->hashes->count();
            $this->seenRequests = $tendering->hashes->where('seen', true)->count();
            $this->answeredRequests = $tendering->hashes->where('answered', true)->where('cancelled', false)->count();
            $this->cancelledOffers = $tendering->hashes->where('cancelled', true)->count();


            $this->suppliers = $tendering->hashes->pluck('supplier')->unique();
        } else {
            abort(404);
        }

        $this->bestOffer();
    }

    public function filter($parameter)
    {
        switch ($parameter) {
            case 'requested':
                $this->title = 'Solicitudes enviadas';
                $this->suppliers = $this->tender->hashes->pluck('supplier')->unique();
                break;
            case 'seen':
                $this->title = 'Solicitudes vistas';
                $this->suppliers = $this->tender->hashes->where('seen', true)->pluck('supplier')->unique();

                break;
            case 'answered':
                $this->title = 'Ofertas válidas';
                $this->suppliers = $this->tender->hashes->where('answered', true)->where('cancelled', false)->pluck('supplier')->unique();
                break;
            case 'cancelled':
                $this->title = 'Ofertas canceladas';
                $this->suppliers = $this->tender->hashes->where('cancelled', true)->pluck('supplier')->unique();
                break;
        }
    }

    public function bestOffer()
    {
        $hashes = $this->tender->hashes->where('answered', true)->where('cancelled', false);
        $offers = [];

        foreach ($hashes as $hash) {
            $offers[] = $hash->offer;
        }

        $cheapest = $offers[0];
        foreach ($offers as $offer) {
            if ($offer->total < $cheapest->total) {
                $cheapest = $offer;
            }
        }

        $hasAllProducts = false;
        foreach ($cheapest->products as $product) {
            if ($product->pivot->quantity == $this->tender->products->where('id', $product->id)->first()->pivot->quantity) {
                $hasAllProducts = true;
            } else {
                $hasAllProducts = false;
                break;
            }
        }

        // dd($hasAllProducts);
    }

    public function render()
    {
        return view('livewire.tenderings.show-finished-tendering');
    }
}
