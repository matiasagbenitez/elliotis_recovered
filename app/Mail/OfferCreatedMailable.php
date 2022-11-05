<?php

namespace App\Mail;

use App\Models\Offer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OfferCreatedMailable extends Mailable
{
    use Queueable, SerializesModels;

    public $offer;
    public $supplier;
    public $tendering_end_date;
    public $subject;

    public function __construct(Offer $offer)
    {
        $this->offer = $offer;
        $this->supplier = $offer->hash->supplier;
        $this->tendering_end_date = $offer->hash->tendering->end_date;
        $this->subject = $offer->hash->supplier->business_name . ' - ¡Oferta enviada con éxito!';
    }

    public function build()
    {
        return $this->view('emails.offer-created');
    }
}
