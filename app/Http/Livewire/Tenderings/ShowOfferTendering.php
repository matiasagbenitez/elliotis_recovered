<?php

namespace App\Http\Livewire\Tenderings;

use App\Models\Hash;
use Livewire\Component;
use App\Models\Tendering;

class ShowOfferTendering extends Component
{
    public $tender;
    public $hash;
    public $offer;
    public $supplier;

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
    }

    public function render()
    {
        return view('livewire.tenderings.show-offer-tendering');
    }
}
