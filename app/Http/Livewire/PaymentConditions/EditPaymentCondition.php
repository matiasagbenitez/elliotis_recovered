<?php

namespace App\Http\Livewire\PaymentConditions;

use Livewire\Component;

class EditPaymentCondition extends Component
{
    public $isOpen = 0;

    public $paymentCondition, $payment_condition_id;

    public $editForm = [
        'name' => '',
        'is_deferred' => false,
    ];

    protected $validationAttributes = [
        'editForm.name' => 'nombre',
        'editForm.is_deferred' => 'es diferido',
    ];

    public function mount($paymentCondition)
    {
        $this->paymentCondition = $paymentCondition;
        $this->payment_condition_id = $paymentCondition->id;
        $this->editForm['name'] = $paymentCondition->name;
        $this->editForm['is_deferred'] = $paymentCondition->is_deferred;
    }

    public function editPaymentCondition()
    {
        $this->resetInputFields();
        $this->openModal();
        $this->editForm['name'] = $this->paymentCondition->name;
        $this->editForm['is_deferred'] = $this->paymentCondition->is_deferred;
    }

    public function openModal()
    {
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    private function resetInputFields()
    {
        $this->editForm = [
            'name' => '',
            'is_deferred' => false,
        ];
        $this->resetErrorBag();
    }

    public function update()
    {
        $this->validate([
            'editForm.name' => 'required|string|unique:payment_conditions,name,' . $this->payment_condition_id,
            'editForm.is_deferred' => 'required|boolean',
        ]);
        $this->paymentCondition->update($this->editForm);
        $this->reset('editForm');
        $this->closeModal();
        $this->emit('success', '¡La condición de pago se ha actualizado con éxito!');
        $this->emitTo('payment-conditions.index-payment-condition', 'refresh');
    }

    public function render()
    {
        return view('livewire.payment-conditions.edit-payment-condition');
    }
}
