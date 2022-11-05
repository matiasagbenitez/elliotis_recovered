<?php

namespace App\Http\Livewire\Unities;

use App\Models\Unity;
use Livewire\Component;

class EditUnity extends Component
{
    public $isOpen = 0;
    public $unity, $unity_id;
    public $editForm = ['name' => '', 'unities' => ''];

    protected $rules = [
        'editForm.name' => 'required|unique:unities,name',
        'editForm.unities' => 'required|numeric|min:1|unique:unities,unities'
    ];

    protected $validationAttributes = [
        'editForm.name' => 'name',
        'editForm.unities' => 'unities'
    ];

    public function mount(Unity $unity)
    {
        $this->unity = $unity;
        $this->unity_id = $unity->id;
        $this->editForm['name'] = $unity->name;
        $this->editForm['unities'] = $unity->unities;
    }

    public function editUnity()
    {
        $this->resetInputFields();
        $this->openModal();
        $this->editForm['name'] = $this->unity->name;
        $this->editForm['unities'] = $this->unity->unities;
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
        $this->editForm = ['name' => '', 'unities' => ''];
        $this->resetErrorBag();
    }

    public function update()
    {
        $this->validate([
            'editForm.name' => 'required|unique:unities,name,' . $this->unity_id,
            'editForm.unities' => 'required|numeric|min:1|unique:unities,unities,' . $this->unity_id
        ]);
        $unity = Unity::find($this->unity_id);
        $unity->update($this->editForm);
        $this->reset('editForm');
        $this->closeModal();
        $this->emit('success', '¡La unidad se ha actualizado con éxito!');
        $this->emitTo('unities.index-unities', 'refresh');
    }

    public function render()
    {
        return view('livewire.unities.edit-unity');
    }
}
