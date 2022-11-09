<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OfferDeletedMailable extends Mailable
{
    use Queueable, SerializesModels;

    public $supplier_business_name;
    public $subject;

    public function __construct($supplier_business_name)
    {
        $this->supplier_business_name = $supplier_business_name;
        $this->subject = $supplier_business_name . ' - ¡Oferta eliminada con éxito!';
    }

    public function build()
    {
        return $this->view('emails.offer-deleted');
    }
}
