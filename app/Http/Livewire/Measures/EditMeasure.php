<?php

namespace App\Http\Livewire\Measures;

use App\Models\Feet;
use App\Models\Inch;
use App\Models\Measure;
use Livewire\Component;

class EditMeasure extends Component
{
    public $isOpen = 0;
    public $hideDiv;
    public $isFav;

    public $measure;

    public $editForm = [
        'name' => '',
        'height' => '',
        'width' => '',
        'length' => '',
        'm2' => '',
    ];

    protected $validationAttributes = [
        'editForm.name' => 'nombre',
        'editForm.height' => 'altura',
        'editForm.width' => 'ancho',
        'editForm.length' => 'largo',
    ];

    public function mount(Measure $measure)
    {
        $this->measure = $measure;
        $this->hideDiv = $measure->is_trunk;
        $this->isFav = $measure->favorite;
        $this->editForm['name'] = $measure->name;
        $this->editForm['height'] = $measure->height;
        $this->editForm['width'] = $measure->width;
        $this->editForm['length'] = $measure->length;
    }

    public function editMeasure()
    {
        $this->resetInputFields();
        $this->openModal();
        $this->hideDiv = $this->measure->is_trunk;
        $this->editForm['name'] = $this->measure->name;
        $this->editForm['height'] = $this->measure->height;
        $this->editForm['width'] = $this->measure->width;
        $this->editForm['length'] = $this->measure->length;
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
        if ($this->hideDiv == 0) {
            $heightName = Inch::where('id', $this->editForm['height'])->first()->name;
            $widthName = Inch::where('id', $this->editForm['width'])->first()->name;
            $lengthName = Feet::where('id', $this->editForm['length'])->first()->name;

            $this->editForm['name'] = $heightName . '"  x  ' . $widthName . '"  x  ' . $lengthName . '\'';

            if ($this->editForm['height'] != '' && $this->editForm['width'] != '' && $this->editForm['length'] != '') {
                $width_cm = Inch::where('id', $this->editForm['width'])->first()->centimeter;
                $length_cm = Feet::where('id', $this->editForm['length'])->first()->centimeter;
                $m2 = ($width_cm * $length_cm) / 10000;
            }

            $this->validate([
                'editForm.name' => 'required|unique:measures,name,' . $this->measure->id,
                'editForm.height' => 'required|numeric',
                'editForm.width' => 'required|numeric',
                'editForm.length' => 'required|numeric',
            ]);

            $this->measure->update([
                'name' => $this->editForm['name'],
                'height' => $this->editForm['height'],
                'width' => $this->editForm['width'],
                'length' => $this->editForm['length'],
                'favorite' => $this->isFav,
                'm2' => $m2 ?? null,
            ]);

            $this->reset('editForm');
            $this->closeModal();
            $this->emit('success', '¡La medida se ha actualizado con éxito!');
            $this->emitTo('measures.index-measures', 'refresh');
        } else {
            $lengthName = Feet::where('id', $this->editForm['length'])->first()->name;

            $this->editForm['name'] = $lengthName . '\'';

            $this->validate([
                'editForm.name' => 'required|unique:measures,name,' . $this->measure->id,
                'editForm.length' => 'required',
            ]);

            $this->measure->update([
                'name' => $this->editForm['name'],
                'is_trunk' => true,
                'height' => null,
                'width' => null,
                'length' => $this->editForm['length'],
                'favorite' => $this->isFav,
            ]);

            $this->reset('editForm');
            $this->closeModal();
            $this->emit('success', '¡La medida se ha actualizado con éxito!');
            $this->emitTo('measures.index-measures', 'refresh');
        }
    }

    public function render()
    {
        $inches = Inch::all();
        $feets = Feet::all();

        return view('livewire.measures.edit-measure', compact('inches', 'feets'));
    }
}
