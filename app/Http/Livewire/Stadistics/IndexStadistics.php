<?php

namespace App\Http\Livewire\Stadistics;

use Livewire\Component;

class IndexStadistics extends Component
{
    public $stadistic_type, $from_datetime, $to_datetime;
    public $filters = [
        'stadistic_type' => '',
        'from_datetime' => '',
        'to_datetime' => '',
    ];

    public $stadistics_types = [
        0 => 'Seleccione un tipo de estadística',
        1 => 'Producción en línea de corte (corte de rollos)',
        2 => 'Producción en machimbradora',
        3 => 'Producción en empaquetadora',
        4 => 'Estadísticas de ventas',
    ];

    public function mount()
    {
        if (request()->has('stadistic_type') && request()->has('from_datetime') && request()->has('to_datetime')) {
            $this->stadistic_type = request()->get('stadistic_type');
            $this->from_datetime = request()->get('from_datetime');
            $this->to_datetime = request()->get('to_datetime');

            $this->filters['stadistic_type'] = request()->get('stadistic_type');
            $this->filters['from_datetime'] = request()->get('from_datetime');
            $this->filters['to_datetime'] = request()->get('to_datetime');
        } else {
            $this->stadistic_type = 0;
            // Primer día del mes
            $this->from_datetime = date('Y-m-01 06:00');
            // Hoy
            $this->to_datetime = date('Y-m-d H:i');
        }
    }

    public function generate()
    {
        try {
            $stadistic_type = (int) $this->stadistic_type;
            $from_datetime = date('Y-m-d H:i', strtotime($this->from_datetime));
            $to_datetime = date('Y-m-d H:i', strtotime($this->to_datetime));

            if ($stadistic_type == 0 || $from_datetime == '' || $to_datetime == '' || $from_datetime > $to_datetime) {
                $this->emit('error', 'Error al generar la estadística. Revise los datos ingresados.');
                return;
            }

            $this->filters['stadistic_type'] = (int) $this->stadistic_type;
            $this->filters['from_datetime'] = date('Y-m-d H:i', strtotime($this->from_datetime));
            $this->filters['to_datetime'] = date('Y-m-d H:i', strtotime($this->to_datetime));

            return redirect()->route('admin.stadistics.index', ['stadistic_type' => $stadistic_type, 'from_datetime' => $from_datetime, 'to_datetime' => $to_datetime]);

        } catch (\Throwable $th) {
            $this->emit('error', 'Error al generar la estadística. Revise los datos ingresados.');
        }
    }

    public function resetFilters()
    {
        $this->stadistic_type = 0;
        $this->from_datetime = '';
        $this->to_datetime = '';
        return redirect()->route('admin.stadistics.index');
    }

    public function render()
    {
        return view('livewire.stadistics.index-stadistics');
    }
}
