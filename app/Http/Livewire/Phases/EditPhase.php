<?php

namespace App\Http\Livewire\Phases;

use App\Models\Phase;
use Livewire\Component;
use Illuminate\Support\Str;

class EditPhase extends Component
{
    public $isOpen = 0;

    public $phase, $phase_id;

    public $editForm = [
        'name' => '',
        'slug' => '',
        'prefix' => ''
    ];

    protected $validationAttributes = [
        'editForm.name' => 'name',
        'editForm.slug' => 'slug',
        'editForm.prefix' => 'prefix'
    ];

    public function mount(Phase $phase)
    {
        $this->phase = $phase;
        $this->phase_id = $phase->id;
        $this->editForm['name'] = $phase->name;
        $this->editForm['slug'] = $phase->slug;
        $this->editForm['prefix'] = $phase->prefix;
    }

    public function editPhase()
    {
        $this->resetInputFields();
        $this->openModal();
        $this->editForm['name'] = $this->phase->name;
        $this->editForm['slug'] = $this->phase->slug;
        $this->editForm['prefix'] = $this->phase->prefix;
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
        $this->editForm['slug'] = Str::slug($this->editForm['name']);
        $this->validate([
            'editForm.name' => 'required|string|unique:phases,name,' . $this->phase_id,
            'editForm.slug' => 'required|string|unique:phases,slug,' . $this->phase_id,
            'editForm.prefix' => 'required|string|unique:phases,prefix,' . $this->phase_id
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
