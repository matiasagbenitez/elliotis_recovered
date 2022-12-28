<?php

namespace App\Http\Livewire\Phases;

use App\Models\Phase;
use Livewire\Component;
use Illuminate\Support\Str;

class CreatePhase extends Component
{
    public $isOpen = 0;

    public $createForm = ['name' => '', 'slug' => '', 'prefix' => ''];

    protected $rules = [
        'createForm.name' => 'required|string|unique:phases,name',
        'createForm.slug' => 'required|string|unique:phases,slug',
        'createForm.prefix' => 'required|string|unique:phases,prefix'
    ];

    protected $validationAttributes = [
        'createForm.name' => 'name',
        'createForm.slug' => 'slug',
        'createForm.prefix' => 'prefix'
    ];

    public function createPhase()
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
        $this->reset('createForm');
        $this->resetErrorBag();
    }

    public function save()
    {
            $this->createForm['slug'] = Str::slug($this->createForm['name']);
            $this->validate();
            // dd($this->createForm);
            Phase::create($this->createForm);
            $this->reset('createForm');
            $this->closeModal();
            $this->emit('success', '¡La etapa se ha creado con éxito!');
            $this->emitTo('phases.index-phases', 'refresh');
    }

    public function render()
    {
        return view('livewire.phases.create-phase');
    }
}
