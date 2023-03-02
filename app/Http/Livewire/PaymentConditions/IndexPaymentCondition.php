<?php

namespace App\Http\Livewire\PaymentConditions;

use App\Models\PaymentConditions;
use Livewire\Component;
use Livewire\WithPagination;

class IndexPaymentCondition extends Component
{
    use WithPagination;
    public $search;

    protected $listeners = ['delete', 'refresh' => 'render'];

    public function delete($id)
    {
        try {
            $payment_condition = PaymentConditions::find($id);
            $payment_condition->delete();
            $this->emit('success', 'La condición de pago se ha eliminado con éxito.');
            $this->render();
        } catch (\Throwable $th) {
            $this->emit('error', 'La condición de pago no se ha podido eliminar.');
        }
    }

    public function render()
    {
        $payment_conditions = PaymentConditions::paginate(10);
        return view('livewire.payment-conditions.index-payment-condition', compact('payment_conditions'));
    }
}
