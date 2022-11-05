<?php

namespace App\Http\Livewire\PucharseParameters\PaymentMethods;

use App\Models\PaymentMethods;
use Livewire\Component;

class IndexPaymentMethods extends Component
{
    public function render()
    {
        $payment_methods = PaymentMethods::all();
        return view('livewire.pucharse-parameters.payment-methods.index-payment-methods', compact('payment_methods'));
    }
}
