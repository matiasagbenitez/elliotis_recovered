<?php

namespace App\Http\Livewire\Phases;

use App\Models\Phase;
use Livewire\Component;

class EditPhase extends Component
{
    public $isOpen = 0;

    public $phase, $phase_id;

    public $editForm = [
        'name' => ''
    ];

    protected $validationAttributes = [
        'editForm.name' => 'name'
    ];

    public function mount(Phase $phase)
    {
        $this->phase = $phase;
        $this->phase_id = $phase->id;
        $this->editForm['name'] = $phase->name;
    }

    public function editPhase()
    {
        $this->resetInputFields();
        $this->openModal();
        $this->editForm['name'] = $this->phase->name;
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
            'editForm.name' => 'required|string|unique:phases,name,' . $this->phase_id
        ]);
        $this->phase->update($this->editForm);
        $this->reset('editForm');
        $this->closeModal();
        $this->emit('success', '¡La fase se ha actualizado con éxito!');
        $this->emitTo('phases.index-phases', 'refresh');
    }

    public function render()
    {
        return view('livewire.phases.edit-phase');
    }
}
