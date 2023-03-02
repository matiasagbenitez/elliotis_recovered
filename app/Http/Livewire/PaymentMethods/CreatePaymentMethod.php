<?php

namespace App\Http\Livewire\PaymentMethods;

use Livewire\Component;
use App\Models\PaymentMethods;

class CreatePaymentMethod extends Component
{
    public $isOpen = 0;

    public $createForm = [
        'name' => '',
    ];

    protected $rules = [
        'createForm.name' => 'required|string|unique:payment_methods,name',
    ];

    protected $validationAttributes = [
        'createForm.name' => 'nombre',
    ];

    public function createPaymentMethod()
    {
        $this->resetInputFields();
        $this->openModal();
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
        $this->createForm = [
            'name' => '',
        ];
        $this->resetErrorBag();
    }

    public function save()
    {
        $this->validate();
        PaymentMethods::create($this->createForm);
        $this->reset('createForm');
        $this->closeModal();
        $this->emit('success', '¡El método de pago se ha creado con éxito!');
        $this->emitTo('payment-methods.index-payment-methods', 'refresh');
    }

    public function render()
    {
        return view('livewire.payment-methods.create-payment-method');
    }
}
