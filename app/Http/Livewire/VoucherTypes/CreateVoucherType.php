<?php

namespace App\Http\Livewire\VoucherTypes;

use Livewire\Component;
use App\Models\VoucherTypes;

class CreateVoucherType extends Component
{
    public $isOpen = 0;
    public $createForm = [
        'name' => '',
    ];

    protected $rules = [
        'createForm.name' => 'required|string|unique:voucher_types,name',
    ];

    protected $validationAttributes = [
        'createForm.name' => 'nombre',
    ];

    public function createVoucherType()
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
        VoucherTypes::create($this->createForm);
        $this->reset('createForm');
        $this->closeModal();
        $this->emit('success', '¡El tipo de comprobante se ha creado con éxito!');
        $this->emitTo('voucher-types.index-voucher-types', 'refresh');
    }

    public function render()
    {
        return view('livewire.voucher-types.create-voucher-type');
    }
}
