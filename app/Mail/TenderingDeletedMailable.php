<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TenderingDeletedMailable extends Mailable
{
    use Queueable, SerializesModels;

    public $supplier_business_name;
    public $tendering_id;
    public $subject;

    public function __construct($supplier, $tendering)
    {
        $this->supplier_business_name = $supplier->business_name;
        $this->tendering_id = $tendering->id;
        $this->subject = $this->supplier_business_name . ' - ¡Concurso de precios anulado!';
    }

    public function build()
    {
        return $this->view('emails.tendering-deleted');
    }
}
