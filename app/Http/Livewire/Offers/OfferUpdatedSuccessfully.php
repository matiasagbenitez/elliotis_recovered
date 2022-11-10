<?php

namespace App\Http\Livewire\Offers;

use App\Models\Hash;
use Livewire\Component;
use App\Mail\OfferUpdatedMailable;
use App\View\Components\GuestLayout;
use Illuminate\Support\Facades\Mail;

class OfferUpdatedSuccessfully extends Component
{
    public $supplier_business_name;
    public $updated_at;
    public $tendering_end_date;
    public $offer;

    public function mount(Hash $hash)
    {
        if (!$hash->is_active) {
            // Abort
            abort(404, 'Hash no válido');
        }

        $this->supplier_business_name = $hash->supplier->business_name;
        $this->updated_at = $hash->offer->updated_at;
        $this->tendering_end_date = $hash->tendering->end_date;
        $this->offer = $hash->offer;

        $this->sendConfirmationEmail();
    }

    public function sendConfirmationEmail()
    {
        $mail = new OfferUpdatedMailable($this->offer);
        Mail::to($this->offer->hash->supplier->email)->send($mail);
    }

    public function render()
    {
        return view('livewire.offers.offer-updated-successfully')->layout(GuestLayout::class);
    }
}
