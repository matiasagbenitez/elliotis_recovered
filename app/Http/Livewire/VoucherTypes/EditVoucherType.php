<?php

namespace App\Http\Livewire\VoucherTypes;

use Livewire\Component;

class EditVoucherType extends Component
{
    public $isOpen = 0;

    public $voucherType, $voucher_type_id;

    public $editForm = [
        'name' => '',
    ];

    protected $validationAttributes = [
        'editForm.name' => 'nombre',
    ];

    public function mount($voucherType)
    {
        $this->voucherType = $voucherType;
        $this->voucher_type_id = $voucherType->id;
        $this->editForm['name'] = $voucherType->name;
    }

    public function editVoucherType()
    {
        $this->resetInputFields();
        $this->openModal();
        $this->editForm['name'] = $this->voucherType->name;
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
            'editForm.name' => 'required|string|unique:voucher_types,name,' . $this->voucher_type_id,
        ]);
        $this->voucherType->update($this->editForm);
        $this->reset('editForm');
        $this->closeModal();
        $this->emit('success', '¡El tipo de comprobante se ha actualizado con éxito!');
        $this->emitTo('voucher-types.index-voucher-types', 'refresh');
    }

    public function render()
    {
        return view('livewire.voucher-types.edit-voucher-type');
    }
}
