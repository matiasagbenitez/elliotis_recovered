<?php

namespace App\Http\Livewire\PaymentMethods;

use Livewire\Component;

class EditPaymentMethod extends Component
{
    public $isOpen = 0;

    public $paymentMethod, $payment_method_id;

    public $editForm = [
        'name' => '',
    ];

    protected $validationAttributes = [
        'editForm.name' => 'nombre',
    ];

    public function mount($paymentMethod)
    {
        $this->paymentMethod = $paymentMethod;
        $this->payment_method_id = $paymentMethod->id;
        $this->editForm['name'] = $paymentMethod->name;
    }

    public function editPaymentMethod()
    {
        $this->resetInputFields();
        $this->openModal();
        $this->editForm['name'] = $this->paymentMethod->name;
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
        ];
        $this->resetErrorBag();
    }

    public function update()
    {
        $this->validate([
            'editForm.name' => 'required|string|unique:payment_methods,name,' . $this->payment_method_id,
        ]);
        $this->paymentMethod->update($this->editForm);
        $this->reset('editForm');
        $this->closeModal();
        $this->emit('success', '¡El método de pago se ha actualizado con éxito!');
        $this->emitTo('payment-methods.index-payment-methods', 'refresh');
    }

    public function render()
    {
        return view('livewire.payment-methods.edit-payment-method');
    }
}
