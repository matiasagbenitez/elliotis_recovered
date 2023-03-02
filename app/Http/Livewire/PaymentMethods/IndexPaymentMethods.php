<?php

namespace App\Http\Livewire\PaymentMethods;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\PaymentMethods;

class IndexPaymentMethods extends Component
{
    use WithPagination;
    public $search;

    protected $listeners = ['delete', 'refresh' => 'render'];

    public function delete($id)
    {
        try {
            $payment_method = PaymentMethods::find($id);
            $payment_method->delete();
            $this->emit('success', 'El método de pago se ha eliminado con éxito.');
            $this->render();
        } catch (\Throwable $th) {
            $this->emit('error', 'El método de pago no se ha podido eliminar.');
        }
    }

    public function render()
    {
        $payment_methods = PaymentMethods::paginate(10);

        return view('livewire.payment-methods.index-payment-methods', compact('payment_methods'));
    }
}
