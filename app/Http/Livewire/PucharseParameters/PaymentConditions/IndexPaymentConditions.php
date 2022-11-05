<?php

namespace App\Http\Livewire\PucharseParameters\PaymentConditions;

use Livewire\Component;
use App\Models\PaymentConditions;

class IndexPaymentConditions extends Component
{
    public function render()
    {
        $payment_conditions = PaymentConditions::all();
        return view('livewire.pucharse-parameters.payment-conditions.index-payment-conditions', compact('payment_conditions'));
    }
}
