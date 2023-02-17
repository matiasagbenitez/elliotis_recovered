<?php

namespace App\Http\Livewire\Stadistics;

use App\Models\Task;
use App\Models\Sublot;
use Livewire\Component;
use App\Models\InputTaskDetail;
use App\Models\OutputTaskDetail;
use App\Http\Services\TimeService;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Crypt;
use Symfony\Component\Console\Output\Output;
use App\Charts\ProduccionEmpaquetadoraChart1;
use App\Charts\ProduccionEmpaquetadoraChart2;

class ProduccionEmpaquetadora extends Component
{
    public $filters;
    public $from_datetime, $to_datetime;

    public $total_tareas_empaquetado, $total_fajas_entrada, $total_fajas_entrada_m2, $cantidad_sublotes_entrada, $productos_entrada, $tiempo_empaquetado_formateado;
    public $total_paquetes_salida, $total_paquetes_salida_m2, $cantidad_sublotes_salida, $productos_salida, $m2_por_hora, $paquetes_por_hora, $top_5_dias;

    public $neutralColors = [
        '#fafafa',
        '#f5f5f5',
        '#e5e5e5',
        '#d4d4d4',
        '#a3a3a3',
        '#737373',
        '#525252',
        '#404040',
        '#262626',
        '#171717',
    ];

    public function mount($filters)
    {
        $this->filters = $filters;
        $this->from_datetime = $filters['from_datetime'];
        $this->to_datetime = $filters['to_datetime'];
        $this->calculate();
    }

    public function calculate()
    {
        try {
            $tareas_empaquetado = Task::where('type_of_task_id', 9)
                ->where('cancelled', false)
                ->where('started_at', '>=', $this->filters['from_datetime'])
                ->where('finished_at', '<=', $this->filters['to_datetime'])
                ->get();

            if ($tareas_empaquetado->count() == 0) {
                $this->total_tareas_empaquetado = 0;
                $this->total_fajas_entrada = 0;
                $this->total_fajas_entrada_m2 = 0;
                $this->cantidad_sublotes_entrada = 0;
                $this->productos_entrada = [];
                $this->tiempo_empaquetado_formateado = 0;
                $this->total_paquetes_salida = 0;
                $this->total_paquetes_salida_m2 = 0;
                $this->cantidad_sublotes_salida = 0;
                $this->productos_salida = [];
                $this->m2_por_hora = 0;
                $this->paquetes_por_hora = 0;
                $this->top_5_dias = [];
                return;
            }

            // Total de tareas de empaquetado
            $total_tareas_empaquetado = $tareas_empaquetado->count() > 0 ? $tareas_empaquetado->count() : 0;
            $this->total_tareas_empaquetado = $total_tareas_empaquetado;

            // Sublotes de entrada
            $sublotes_entrada = InputTaskDetail::whereIn('task_id', $tareas_empaquetado->pluck('id'))->get();
            $cantidad_sublotes_entrada = $sublotes_entrada->count() > 0 ? $sublotes_entrada->count() : 0;
            $this->cantidad_sublotes_entrada = $cantidad_sublotes_entrada;

            $total_fajas_entrada = $sublotes_entrada->sum('consumed_quantity');
            $this->total_fajas_entrada = $total_fajas_entrada;

            $total_fajas_entrada_m2 = $sublotes_entrada->sum('m2');
            $this->total_fajas_entrada_m2 = $total_fajas_entrada_m2;

            // Productos de entrada
            $productos_entrada = [];
            foreach ($sublotes_entrada as $item) {
                $sublote = Sublot::find($item->sublot_id);
                $producto = $sublote->product;
                if (!isset($productos_entrada[$producto->id])) {
                    $productos_entrada[$producto->id] = [
                        'nombre' => $producto->name,
                        'medida' => $producto->productType->measure->name,
                        'cantidad' => $item->consumed_quantity,
                        'm2' => $item->m2,
                    ];
                }
                $productos_entrada[$producto->id]['cantidad'] += $item->consumed_quantity;
                $productos_entrada[$producto->id]['m2'] += $item->m2;
            }
            $this->productos_entrada = $productos_entrada;

            // Horas de empaquetado
            $tiempo_empaquetado = 0;
            foreach ($tareas_empaquetado as $item) {
                $time_diff = strtotime($item->finished_at) - strtotime($item->started_at);
                $tiempo_empaquetado += $time_diff;
            }
            $tiempo_empaquetado_formateado = TimeService::secondsToHoursAndMinutes($tiempo_empaquetado);
            $this->tiempo_empaquetado_formateado = $tiempo_empaquetado_formateado;

            // Sublotes de salida
            $sublotes_salida = OutputTaskDetail::whereIn('task_id', $tareas_empaquetado->pluck('id'))->get();
            // dd($sublotes_salida);
            $total_paquetes_salida = $sublotes_salida->sum('produced_quantity');
            $this->total_paquetes_salida = $total_paquetes_salida;
            $total_paquetes_salida_m2 = $sublotes_salida->sum('m2');
            $this->total_paquetes_salida_m2 = $total_paquetes_salida_m2;
            $cantidad_sublotes_salida = $sublotes_salida->count();
            $this->cantidad_sublotes_salida = $cantidad_sublotes_salida;

            // Productos de salida
            $productos_salida = [];
            foreach ($sublotes_salida as $item) {
                $sublote = Sublot::find($item->sublot_id);
                $producto = $sublote->product;
                if (!isset($productos_salida[$producto->id])) {
                    $productos_salida[$producto->id] = [
                        'nombre' => $producto->name,
                        'medida' => $producto->productType->measure->name,
                        'cantidad' => $item->produced_quantity,
                        'm2' => $item->m2,
                    ];
                } else {
                    $productos_salida[$producto->id]['cantidad'] += $item->produced_quantity;
                    $productos_salida[$producto->id]['m2'] += $item->m2;
                }
            }
            $this->productos_salida = $productos_salida;

            // M2 por hora
            $m2_por_hora = $tiempo_empaquetado > 0 ? round($total_fajas_entrada_m2 / ($tiempo_empaquetado / 3600), 2) : 0;
            $this->m2_por_hora = $m2_por_hora;

            // Paquetes por hora
            $paquetes_por_hora = $tiempo_empaquetado > 0 ? round($total_paquetes_salida / ($tiempo_empaquetado / 3600), 2) : 0;
            $this->paquetes_por_hora = $paquetes_por_hora;

            // TOP 5 días con más producción
            $top_5_dias = [];
            $dias = [];
            foreach ($tareas_empaquetado as $item) {
                $fecha = Date::parse($item->finished_at)->format('d/m/Y');
                if (!isset($dias[$fecha])) {
                    $dias[$fecha] = [
                        'fecha' => $fecha,
                        'paquetes' => $item->lot->sublots->sum('initial_quantity'),
                        'm2' => $item->lot->sublots->sum('initial_m2'),
                    ];
                } else {
                    $dias[$fecha]['paquetes'] += $item->lot->sublots->sum('initial_quantity');
                    $dias[$fecha]['m2'] += $item->lot->sublots->sum('initial_m2');
                }
            }
            $dias_ordenados = collect($dias)->sortByDesc('initial_quantity');
            $top_5_dias = $dias_ordenados->take(5);
            $this->top_5_dias = $top_5_dias;

        } catch (\Throwable $th) {
            $this->emit('error', $th->getMessage());
        }
    }

    public function render()
    {
        $labels = collect($this->productos_entrada)->pluck('medida');
        $data = collect($this->productos_entrada)->pluck('m2');

        $chart1 = new ProduccionEmpaquetadoraChart1;
        $chart1->labels($labels)->options([
                'legend' => [
                    'display' => false,
                ],
                'title' => [
                    'display' => true,
                    'text' => 'Fajas machimbradas empaquetadas',
                    'fontSize' => 20,
                ],
            ]);
        $colors = range(1, count($labels));
        shuffle($colors);
        $colorArray = [];

        // Choose colors from $this->neutralColors without repeating
        foreach ($colors as $color) {
            // Not repeat colors from $this->neutralColors
            if (!in_array($this->neutralColors[$color], $colorArray)) {
                $colorArray[] = $this->neutralColors[$color];
            }
        }

        $chart1->dataset('m² de fajas machimbradas procesadas', 'bar', $data)
            ->backgroundColor($colorArray)
            ->color($colorArray);

        $labels = collect($this->productos_salida)->pluck('medida');
        $data = collect($this->productos_salida)->pluck('cantidad');

        $chart2 = new ProduccionEmpaquetadoraChart2;
        $chart2->labels($labels)->options([
            'legend' => [
                'display' => false,
            ],
            'title' => [
                'display' => true,
                'text' => 'Paquetes machimbrados',
                'fontSize' => 20,
            ],
        ]);
        $colors = range(1, count($labels));
        shuffle($colors);
        $colorArray = [];

        // Choose colors from $this->neutralColors without repeating
        foreach ($colors as $color) {
            // Not repeat colors from $this->neutralColors
            if (!in_array($this->neutralColors[$color], $colorArray)) {
                $colorArray[] = $this->neutralColors[$color];
            }
        }

        $chart2->dataset('Paquetes machimbrados', 'bar', $data)
            ->color($colorArray)
            ->backgroundColor($colorArray);

        return view('livewire.stadistics.produccion-empaquetadora', [
            'chart1' => $chart1,
            'chart2' => $chart2,
        ]);
    }


    public function generatePDF()
    {
        try {
            // Encriptar filtros
            $filters = Crypt::encrypt($this->filters);

            $stats = [
                'total_tareas_empaquetado' => $this->total_tareas_empaquetado,
                'total_fajas_entrada' => $this->total_fajas_entrada,
                'total_fajas_entrada_m2' => $this->total_fajas_entrada_m2,
                'cantidad_sublotes_entrada' => $this->cantidad_sublotes_entrada,
                'productos_entrada' => $this->productos_entrada,
                'tiempo_empaquetado_formateado' => $this->tiempo_empaquetado_formateado,
                'total_paquetes_salida' => $this->total_paquetes_salida,
                'total_paquetes_salida_m2' => $this->total_paquetes_salida_m2,
                'cantidad_sublotes_salida' => $this->cantidad_sublotes_salida,
                'productos_salida' => $this->productos_salida,
                'm2_por_hora' => $this->m2_por_hora,
                'paquetes_por_hora' => $this->paquetes_por_hora,
                'top_5_dias' => $this->top_5_dias,
            ];

            $stats_encrypted = Crypt::encrypt($stats);

            return redirect()->route('admin.produccion-empaquetadora.pdf', [
                'filters' => $filters,
                'stats' => $stats_encrypted,
            ]);

        } catch (\Throwable $th) {
            $this->emit('error', 'Error al generar la estadística. Revise los datos ingresados.');
        }
    }
}
