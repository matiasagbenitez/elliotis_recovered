<?php

namespace App\Http\Livewire\IvaConditions;

use Livewire\Component;
use App\Models\IvaCondition;

class EditIvaCondition extends Component
{
    public $isOpen = 0;

    public $condition, $condition_id;
    public $editForm = ['name' => '', 'discriminate' => ''];

    protected $validationAttributes = [
        'editForm.name' => 'name',
        'editForm.discriminate' => 'discriminate'
    ];

    public function mount(IvaCondition $condition)
    {
        $this->condition = $condition;
        $this->condition_id = $condition->id;
        $this->editForm['name'] = $condition->name;
        $this->editForm['discriminate'] = $condition->discriminate;
    }

    public function editIvaCondition()
    {
        $this->resetInputFields();
        $this->openModal();
        $this->editForm['name'] = $this->condition->name;
        $this->editForm['discriminate'] = $this->condition->discriminate;
    }

    public function openModal()
    {
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    public function resetInputFields()
    {
        $this->resetErrorBag();
    }

    public function update()
    {
        $this->validate([
            'editForm.name' => 'required|unique:iva_conditions,name,' . $this->condition_id,
            'editForm.discriminate' => 'required|boolean'
        ]);
        $condition = IvaCondition::find($this->condition_id);
        $condition->update($this->editForm);
        $this->reset('editForm');
        $this->closeModal();
        $this->emit('success', '¡La condición de IVA se ha actualizado con éxito!');
        $this->emitTo('iva-conditions.index-iva-conditions', 'refresh');
    }

    public function render()
    {
        return view('livewire.iva-conditions.edit-iva-condition');
    }
}
