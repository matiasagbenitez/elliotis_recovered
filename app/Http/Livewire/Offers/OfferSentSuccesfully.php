<?php

namespace App\Http\Livewire\Offers;

use App\Mail\OfferCreatedMailable;
use App\Models\Hash;
use Livewire\Component;
use App\View\Components\GuestLayout;
use Illuminate\Support\Facades\Mail;

class OfferSentSuccesfully extends Component
{
    public $supplier_business_name;
    public $answered_at;
    public $tendering_end_date;
    public $offer;

    public function mount(Hash $hash)
    {
        $this->supplier_business_name = $hash->supplier->business_name;
        $this->answered_at = $hash->answered_at;
        $this->tendering_end_date = $hash->tendering->end_date;
        $this->offer = $hash->offer;

        if (!$hash->confirmation_sent) {
            $this->sendConfirmationEmail();
            $hash->update([
                'confirmation_sent' => true,
            ]);
        }
    }

    public function sendConfirmationEmail()
    {
        $mail = new OfferCreatedMailable($this->offer);
        Mail::to($this->offer->hash->supplier->email)->send($mail);
    }

    public function render()
    {
        return view('livewire.offers.offer-sent-succesfully')->layout(GuestLayout::class);
    }
}
