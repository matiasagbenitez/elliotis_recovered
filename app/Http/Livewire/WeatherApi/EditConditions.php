<?php

namespace App\Http\Livewire\WeatherApi;

use App\Models\WeatherApi;
use Livewire\Component;

class EditConditions extends Component
{
    public $isOpen = 0;
    public $condition, $condition_id;

    public $editForm = [
        'temp' => '',
        'rain_prob' => '',
        'rain_mm' => '',
        'humidity' => '',
        'wind_speed' => '',
        'days_in_row' => '',
        'max_conditions' => ''
    ];

    protected $validationAttributes = [
        'editForm.temp' => 'temperatura',
        'editForm.rain_prob' => 'probabilidad de lluvia',
        'editForm.rain_mm' => 'cantidad de lluvia',
        'editForm.humidity' => 'humedad',
        'editForm.wind_speed' => 'velocidad del viento',
        'editForm.days_in_row' => 'días en fila',
        'editForm.max_conditions' => 'máximo de condiciones'
    ];

    public function mount()
    {
        $this->condition = WeatherApi::first();
        $this->condition_id = $this->condition->id;
        $this->editForm['temp'] = $this->condition->temp;
        $this->editForm['rain_prob'] = $this->condition->rain_prob;
        $this->editForm['rain_mm'] = $this->condition->rain_mm;
        $this->editForm['humidity'] = $this->condition->humidity;
        $this->editForm['wind_speed'] = $this->condition->wind_speed;
        $this->editForm['days_in_row'] = $this->condition->days_in_row;
        $this->editForm['max_conditions'] = $this->condition->max_conditions;
    }

    public function editConditions()
    {
        $this->resetInputFields();
        $this->openModal();
        $this->editForm['temp'] = $this->condition->temp;
        $this->editForm['rain_prob'] = $this->condition->rain_prob;
        $this->editForm['rain_mm'] = $this->condition->rain_mm;
        $this->editForm['humidity'] = $this->condition->humidity;
        $this->editForm['wind_speed'] = $this->condition->wind_speed;
        $this->editForm['days_in_row'] = $this->condition->days_in_row;
        $this->editForm['max_conditions'] = $this->condition->max_conditions;
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
            'temp' => '',
            'rain_prob' => '',
            'rain_mm' => '',
            'humidity' => '',
            'wind_speed' => '',
            'days_in_row' => '',
            'max_conditions' => ''
        ];
        $this->resetErrorBag();
    }

    public function update()
    {
            $this->validate([
                'editForm.temp' => 'required|numeric|min:-50|max:50',
                'editForm.rain_prob' => 'required|numeric|min:0|max:1',
                'editForm.rain_mm' => 'required|numeric|min:0|max:100',
                'editForm.humidity' => 'required|numeric|min:0|max:1',
                'editForm.wind_speed' => 'required|numeric|min:0|max:100',
                'editForm.days_in_row' => 'required|numeric|min:1|max:5',
                'editForm.max_conditions' => 'required|numeric|min:1|max:5',
            ]);
            $this->condition->update($this->editForm);
            $this->reset('editForm');
            $this->closeModal();
            $this->emit('success_update', '¡Las condiciones se han actualizado con éxito!');
            return redirect()->route('admin.api.index');

    }

    public function render()
    {
        return view('livewire.weather-api.edit-conditions');
    }
}
