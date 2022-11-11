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
            $this->answeredRequests = $tendering->hashes->where('answered', true)->count();
            $this->cancelledOffers = $tendering->hashes->where('cancelled', true)->count();


            $this->suppliers = $tendering->hashes->pluck('supplier')->unique();
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
                $this->title = 'Solicitudes contestadas';
                $this->suppliers = $this->tender->hashes->where('answered', true)->pluck('supplier')->unique();
                break;
            case 'cancelled':
                $this->title = 'Solicitudes canceladas';
                $this->suppliers = $this->tender->hashes->where('cancelled', true)->pluck('supplier')->unique();
                break;
        }
    }

    public function render()
    {
        return view('livewire.tenderings.show-finished-tendering');
    }
}
