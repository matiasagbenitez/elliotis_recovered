<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TenderingCreatedMailable extends Mailable
{
    use Queueable, SerializesModels;

    public $supplier;
    public $hash;
    public $subject;

    public function __construct($supplier, $hash)
    {
        $this->supplier = $supplier;
        $this->hash = $hash;
        $this->subject = $supplier->business_name . ' - ¡Nuevo concurso de precios!';
    }

    public function build()
    {
        return $this->view('emails.tendering-created');
    }
}
