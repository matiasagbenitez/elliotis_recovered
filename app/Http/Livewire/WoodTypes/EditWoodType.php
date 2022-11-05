<?php

namespace App\Http\Livewire\WoodTypes;

use Livewire\Component;
use App\Models\WoodType;

class EditWoodType extends Component
{
    public $isOpen = 0;
    public $wood_type, $wood_type_id;

    public $editForm = ['name' => ''];

    protected $messages = [
        'editForm.name.required' => 'El campo nombre es obligatorio.',
        'editForm.name.unique' => 'El tipo de madera ya existe.',
    ];

    public function mount(WoodType $wood_type)
    {
        $this->wood_type = $wood_type;
        $this->wood_type_id = $wood_type->id;
        $this->editForm['name'] = $wood_type->name;
    }

    public function editWoodType()
    {
        $this->resetInputFields();
        $this->openModal();
        $this->editForm['name'] = $this->wood_type->name;
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
        $this->editForm = ['name' => ''];
        $this->resetErrorBag();
    }


    public function update()
    {
        $this->validate([
            'editForm.name' => 'required|unique:wood_types,name,' . $this->wood_type_id,
        ]);
        $this->wood_type->update($this->editForm);
        $this->reset('editForm');
        $this->closeModal();
        $this->emit('success', '¡El tipo de madera se ha actualizado con éxito!');
        $this->emitTo('wood-types.index-wood-types', 'refresh');
    }

    public function render()
    {
        return view('livewire.wood-types.edit-wood-type');
    }
}
