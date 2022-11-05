<?php

namespace App\Http\Livewire\Measures;

use App\Models\Feet;
use App\Models\Inch;
use App\Models\Measure;
use Livewire\Component;

class CreateMeasure extends Component
{
    public $isOpen = 0;
    public $showDiv = 1;
    public $isFav = 0;

    public $createForm = ['name' => '-', 'height' => '', 'width' => '', 'length' => ''];

    public $inches = [], $feets = [];

    protected $rules = [
        'createForm.name' => 'required|unique:measures,name',
        'createForm.height' => 'required',
        'createForm.width' => 'required',
        'createForm.length' => 'required',
    ];

    protected $validationAttributes = [
        'createForm.name' => 'name',
        'createForm.height' => 'height',
        'createForm.width' => 'width',
        'createForm.length' => 'length',
    ];

    public function createMeasure()
    {
        $this->resetInputFields();
        $this->openModal();
        $this->inches = Inch::all();
        $this->feets = Feet::all();
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
        $this->createForm = ['name' => '-', 'height' => '', 'width' => '', 'length' => '', 'favorite' => ''];
        $this->resetErrorBag();
    }

    public function save()
    {
        if ($this->showDiv == 1) {
            $this->validate();

            $heightName = Inch::where('id', $this->createForm['height'])->first()->name;
            $widthName = Inch::where('id', $this->createForm['width'])->first()->name;
            $lengthName = Feet::where('id', $this->createForm['length'])->first()->name;

            $this->createForm['name'] = $heightName . '"  x  ' . $widthName . '"  x  ' . $lengthName . '\'';

            $this->validate();

            Measure::create([
                'name' => $this->createForm['name'],
                'height' => $this->createForm['height'],
                'width' => $this->createForm['width'],
                'length' => $this->createForm['length'],
                'favorite' => $this->isFav,
            ]);

            $this->reset('createForm');
            $this->closeModal();
            $this->emit('success', '¡La medida se ha creado con éxito!');
            $this->emitTo('measures.index-measures', 'refresh');

        } else {

            $lengthName = Feet::where('id', $this->createForm['length'])->first()->name;

            $this->createForm['name'] = $lengthName . '\'';

            $this->validate([
                'createForm.name' => 'required|unique:measures,name',
                'createForm.length' => 'required',
            ]);

            Measure::create([
                'name' => $this->createForm['name'],
                'is_trunk' => true,
                'height' => null,
                'width' => null,
                'length' => $this->createForm['length'],
                'favorite' => $this->isFav,
            ]);

            $this->reset('createForm');
            $this->closeModal();
            $this->emit('success', '¡La medida se ha creado con éxito!');
            $this->emitTo('measures.index-measures', 'refresh');

        }
    }

    public function render()
    {
        return view('livewire.measures.create-measure');
    }
}
