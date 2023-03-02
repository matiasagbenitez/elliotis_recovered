<?php

namespace App\Http\Livewire\PaymentConditions;

use Livewire\Component;
use App\Models\PaymentConditions;

class CreatePaymentCondition extends Component
{
    public $isOpen = 0;

    public $createForm = [
        'name' => '',
        'is_deferred' => false,
    ];

    protected $rules = [
        'createForm.name' => 'required|string|unique:payment_conditions,name',
        'createForm.is_deferred' => 'required|boolean',
    ];

    protected $validationAttributes = [
        'createForm.name' => 'nombre',
        'createForm.is_deferred' => 'es diferido',
    ];

    public function createPaymentCondition()
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
            'is_deferred' => false,
        ];
        $this->resetErrorBag();
    }

    public function save()
    {
        $this->validate();
        PaymentConditions::create($this->createForm);
        $this->reset('createForm');
        $this->closeModal();
        $this->emit('success', '¡La condición de pago se ha creado con éxito!');
        $this->emitTo('payment-conditions.index-payment-condition', 'refresh');
    }

    public function render()
    {
        return view('livewire.payment-conditions.create-payment-condition');
    }
}
