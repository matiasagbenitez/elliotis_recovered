<?php

namespace App\Http\Livewire\Areas;

use App\Models\Area;
use Livewire\Component;

class EditArea extends Component
{
    public $isOpen = 0;

    public $area, $area_id;

    public $editForm = [
        'name' => ''
    ];

    protected $validationAttributes = [
        'area.name' => 'name'
    ];

    public function mount(Area $area)
    {
        $this->area = $area;
        $this->area_id = $area->id;
        $this->editForm['name'] = $area->name;
    }

    public function editArea()
    {
        $this->resetInputFields();
        $this->openModal();
        $this->editForm['name'] = $this->area->name;
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
            'editForm.name' => 'required|string|unique:areas,name,' . $this->area_id
        ]);
        $this->area->update($this->editForm);
        $this->reset('editForm');
        $this->closeModal();
        $this->emit('success', '¡El área se ha actualizado con éxito!');
        $this->emitTo('areas.index-areas', 'refresh');
    }

    public function render()
    {
        return view('livewire.areas.edit-area');
    }
}
