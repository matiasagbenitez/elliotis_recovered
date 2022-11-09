<?php

namespace App\Http\Livewire\Offers;

use App\Models\Hash;
use Livewire\Component;
use App\Mail\OfferDeletedMailable;
use App\View\Components\GuestLayout;
use Illuminate\Support\Facades\Mail;

class OfferDeletedSuccessfully extends Component
{
    public $hash;
    public $supplier_business_name;

    public function mount(Hash $hash)
    {
        $this->hash = $hash;
        $this->supplier_business_name = $hash->supplier->business_name;
        $this->sendConfirmationEmail();
    }

    public function sendConfirmationEmail()
    {
        $mail = new OfferDeletedMailable($this->supplier_business_name);
        Mail::to($this->hash->supplier->email)->send($mail);
    }

    public function render()
    {
        return view('livewire.offers.offer-deleted-successfully')->layout(GuestLayout::class);
    }
}
