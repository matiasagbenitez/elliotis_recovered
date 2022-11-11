<?php

namespace App\Http\Livewire\Tenderings;

use App\Models\Offer;
use App\Models\Tendering;
use Livewire\Component;
use Termwind\Components\Dd;

class ShowFinishedTendering extends Component
{
    public $tender, $hashes;

    public $requestedSuppliers, $seenRequests, $answeredRequests, $cancelledOffers;
    public $title = 'Solicitudes enviadas', $suppliers;
    public $offerTitle = 'Todas las ofertas recibidas', $offers, $offersList = [];

    // All offers
    public $totalOffers;

    // Productos OK y cantidades OK
    public $productsOkQuantitiesOk;

    // Productos OK y cantidades NO
    public $productsOkQuantitiesNo;

    // Productos NO y cantidades OK
    public $productsNoQuantitiesOk;

    // Productos NO y cantidades NO
    public $productsNoQuantitiesNo;

    public function mount(Tendering $tendering)
    {
        if ($tendering->is_finished) {
            $this->tender = $tendering;
            $this->hashes = $tendering->hashes;

            $this->suppliers = $tendering->hashes->pluck('supplier')->unique();

            $this->requestedSuppliers = $tendering->hashes->count();
            $this->seenRequests = $tendering->hashes->where('seen', true)->count();
            $this->answeredRequests = $tendering->hashes->where('answered', true)->where('cancelled', false)->count();
            $this->cancelledOffers = $tendering->hashes->where('cancelled', true)->count();

            // Collection of offers from the tendering related with the hashes
            $this->offers = Offer::whereHas('hash', function ($query) use ($tendering) {
                $query->where('tendering_id', $tendering->id);
            })->get();

            $this->offersList = $this->offers;

            $this->totalOffers = $this->offers->count();

            // Products OK and quantities OK
            $this->productsOkQuantitiesOk = $this->offers->where('products_ok', true)->where('quantities_ok', true)->count();

            // Products OK and quantities NO
            $this->productsOkQuantitiesNo = $this->offers->where('products_ok', true)->where('quantities_ok', false)->count();

            // Products NO and quantities OK
            $this->productsNoQuantitiesOk = $this->offers->where('products_ok', false)->where('quantities_ok', true)->count();

            // Products NO and quantities NO
            $this->productsNoQuantitiesNo = $this->offers->where('products_ok', false)->where('quantities_ok', false)->count();

        } else {
            abort(404);
        }
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

    public function filterOffers($par)
    {
        switch ($par) {
            case 'all':
                $this->offerTitle = 'Todas las ofertas recibidas';
                $this->offersList = $this->offers;
                break;
            case 'productsOkQuantitiesOk':
                $this->offerTitle = 'Productos OK y cantidades OK';
                $this->offersList = $this->offers->where('products_ok', true)->where('quantities_ok', true);
                break;
            case 'productsOkQuantitiesNo':
                $this->offerTitle = 'Productos OK y cantidades NO';
                $this->offersList = $this->offers->where('products_ok', true)->where('quantities_ok', false);
                break;
            case 'productsNoQuantitiesOk':
                $this->offerTitle = 'Productos NO y cantidades OK';
                $this->offersList = $this->offers->where('products_ok', false)->where('quantities_ok', true);
                break;
            case 'productsNoQuantitiesNo':
                $this->offerTitle = 'Productos NO y cantidades NO';
                $this->offersList = $this->offers->where('products_ok', false)->where('quantities_ok', false);
                break;
        }
    }

    public function render()
    {
        return view('livewire.tenderings.show-finished-tendering');
    }
}
