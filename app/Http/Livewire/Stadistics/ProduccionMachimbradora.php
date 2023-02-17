<?php

namespace App\Http\Livewire\Stadistics;

use App\Models\Task;
use App\Models\Sublot;
use Livewire\Component;
use Psy\Output\OutputPager;
use App\Models\InputTaskDetail;
use App\Models\OutputTaskDetail;
use App\Http\Services\TimeService;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Crypt;
use App\Charts\ProduccionMachimbradoraChart1;
use App\Charts\ProduccionMachimbradoraChart2;
use App\Charts\ProduccionMachimbradoraChart3;

class ProduccionMachimbradora extends Component
{
    public $filters;
    public $from_datetime, $to_datetime;

    public $total_tareas_machimbrado, $total_fajas_entrada, $total_fajas_entrada_m2, $cantidad_sublotes_entrada, $productos_machimbrados_entrada;
    public $tiempo_machimbrado_formateado;
    public $total_fajas_salida, $total_fajas_salida_m2, $cantidad_sublotes_salida, $productos_machimbrados_salida;
    public $m2_x_hora, $tasa_produccion, $tasa_produccion_m2, $top_5_dias;
    public $dias_m2;

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
        $this->from_datetime = Date::parse($this->filters['from_datetime'])->format('d/m/Y H:i') . 'hs';
        $this->to_datetime = Date::parse($this->filters['to_datetime'])->format('d/m/Y H:i') . 'hs';
        $this->calculate();
    }

    public function calculate()
    {
        try {
            $tareas_machimbrado = Task::where('type_of_task_id', 6)
                ->where('cancelled', false)
                ->where('started_at', '>=', $this->filters['from_datetime'])
                ->where('finished_at', '<=', $this->filters['to_datetime'])
                ->get();

            // Si no hay tareas de machimbrado, no hay nada que calcular
            if ($tareas_machimbrado->count() == 0) {
                return;
            }

            // Total de tareas de machimbrado
            $total_tareas_machimbrado = $tareas_machimbrado->count();
            $this->total_tareas_machimbrado = $total_tareas_machimbrado;

            // Sublotes de entrada
            $sublotes_entrada = InputTaskDetail::whereIn('task_id', $tareas_machimbrado->pluck('id'))->get();
            $total_fajas_entrada = $sublotes_entrada->sum('consumed_quantity');
            $this->total_fajas_entrada = $total_fajas_entrada;
            $total_fajas_entrada_m2 = $sublotes_entrada->sum('m2');
            $this->total_fajas_entrada_m2 = $total_fajas_entrada_m2;
            $cantidad_sublotes_entrada = $sublotes_entrada->count();
            $this->cantidad_sublotes_entrada = $cantidad_sublotes_entrada;

            // Productos machimbrados
            $productos_machimbrados_entrada = [];
            foreach ($sublotes_entrada as $item) {
                $sublote = Sublot::find($item->sublot_id);
                $producto = $sublote->product;
                if (!isset($productos_machimbrados_entrada[$producto->id])) {
                    $productos_machimbrados_entrada[$producto->id] = [
                        'producto' => $producto->name,
                        'medida' => $producto->productType->measure->name,
                        'cantidad' => $item->consumed_quantity,
                        'm2' => $item->m2,
                    ];
                } else {
                    $productos_machimbrados_entrada[$producto->id]['cantidad'] += $item->consumed_quantity;
                    $productos_machimbrados_entrada[$producto->id]['m2'] += $item->m2;
                }
            }
            $this->productos_machimbrados_entrada = $productos_machimbrados_entrada;

            // Horas de machimbrado
            $tiempo_machimbrado = 0;
            foreach ($tareas_machimbrado as $tarea) {
                $time_diff = strtotime($tarea->finished_at) - strtotime($tarea->started_at);
                $tiempo_machimbrado += $time_diff;
            }
            $tiempo_machimbrado_formateado = TimeService::secondsToHoursAndMinutes($tiempo_machimbrado);
            $this->tiempo_machimbrado_formateado = $tiempo_machimbrado_formateado;

            // Colección de sublotes de fajas machimbradas
            $sublotes_salida = OutputTaskDetail::whereIn('task_id', $tareas_machimbrado->pluck('id'))->get();
            $total_fajas_salida = $sublotes_salida->sum('produced_quantity');
            $this->total_fajas_salida = $total_fajas_salida;
            $total_fajas_salida_m2 = $sublotes_salida->sum('m2');
            $this->total_fajas_salida_m2 = $total_fajas_salida_m2;
            $cantidad_sublotes_salida = $sublotes_salida->count();
            $this->cantidad_sublotes_salida = $cantidad_sublotes_salida;

            // Fajas machimbradas y cantidades
            $productos_machimbrados_salida = [];
            foreach ($sublotes_salida as $item) {
                $sublote = Sublot::find($item->sublot_id);
                $producto = $sublote->product;
                if (!isset($productos_machimbrados_salida[$producto->id])) {
                    $productos_machimbrados_salida[$producto->id] = [
                        'producto' => $producto->name,
                        'medida' => $producto->productType->measure->name,
                        'cantidad' => $item->produced_quantity,
                        'm2' => $item->m2,
                    ];
                } else {
                    $productos_machimbrados_salida[$producto->id]['cantidad'] += $item->produced_quantity;
                    $productos_machimbrados_salida[$producto->id]['m2'] += $item->m2;
                }
            }
            $this->productos_machimbrados_salida = $productos_machimbrados_salida;

            // M2 x hora
            $m2_x_hora = $tiempo_machimbrado > 0 ? round($total_fajas_salida_m2 / ($tiempo_machimbrado / 3600), 2) : 0;
            $this->m2_x_hora = $m2_x_hora;

            // Tasa de producción
            $tasa_produccion = $total_fajas_entrada > 0 ? round($total_fajas_salida / $total_fajas_entrada * 100, 2) : 0;
            $this->tasa_produccion = $tasa_produccion;

            // Tasa de producción m2
            $tasa_produccion_m2 = $total_fajas_entrada_m2 > 0 ? round($total_fajas_salida_m2 / $total_fajas_entrada_m2 * 100, 2) : 0;
            $this->tasa_produccion_m2 = $tasa_produccion_m2;

            // Top 5 días con mayor producción
            $top_5_dias = [];
            $dias = [];
            foreach ($tareas_machimbrado as $tarea) {
                $fecha = Date::parse($tarea->started_at)->format('d/m/Y');
                if (!isset($dias[$fecha])) {
                    $dias[$fecha] = [
                        'fecha' => $fecha,
                        'm2' => $tarea->lot->sublots->sum('initial_m2'),
                    ];
                } else {
                    $dias[$fecha]['m2'] += $tarea->lot->sublots->sum('initial_m2');
                }
            }
            $dias_ordenados = collect($dias)->sortByDesc('m2');
            $top_5_dias = $dias_ordenados->take(5);
            $this->top_5_dias = $top_5_dias;

            // Agrupar por día y sumar m2
            $dias_m2 = [];
            foreach ($tareas_machimbrado as $tarea) {
                $fecha = Date::parse($tarea->started_at)->format('d/m/Y');

                if (!isset($dias_m2[$fecha])) {
                    $dias_m2[$fecha] = [
                        'fecha' => $fecha,
                        'm2' => $tarea->lot->sublots->sum('initial_m2'),
                        'duration' => strtotime($tarea->finished_at) - strtotime($tarea->started_at),
                        'horas' => round((strtotime($tarea->finished_at) - strtotime($tarea->started_at)) / 3600, 2),
                    ];
                } else {
                    $dias_m2[$fecha]['m2'] += $tarea->lot->sublots->sum('initial_m2');
                    $dias_m2[$fecha]['duration'] += strtotime($tarea->finished_at) - strtotime($tarea->started_at);
                    $dias_m2[$fecha]['horas'] += round((strtotime($tarea->finished_at) - strtotime($tarea->started_at)) / 3600, 2);
                }
            }
            $this->dias_m2 = $dias_m2;
        } catch (\Throwable $th) {
            $this->emit('error', 'Error al cargar los datos de la línea de corte');
        }
    }

    public function generatePDF()
    {
        try {
            // Encriptar filtros
            $filters = Crypt::encrypt($this->filters);

            return redirect()->route('admin.produccion-machimbradora.pdf', [
                // 'stadistic_type' => $this->filters['stadistic_type'],
                // 'from_datetime' => $this->filters['from_datetime'],
                // 'to_datetime' => $this->filters['to_datetime']
                'filters' => $filters
            ]);

        } catch (\Throwable $th) {
            $this->emit('error', 'Error al generar la estadística. Revise los datos ingresados.');
        }
    }

    public function render()
    {
        $labels = collect($this->productos_machimbrados_entrada)->pluck('medida');
        $data = collect($this->productos_machimbrados_entrada)->pluck('m2');

        $chart1 = new ProduccionMachimbradoraChart1;
        $chart1->labels($labels)->options([
                'legend' => [
                    'display' => false,
                ],
                'title' => [
                    'display' => true,
                    'text' => 'Fajas secas procesadas',
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

        $chart1->dataset('m² de fajas secas procesadas', 'bar', $data)
            ->backgroundColor($colorArray)
            ->color($colorArray);

        $labels = collect($this->productos_machimbrados_salida)->pluck('medida');
        $data = collect($this->productos_machimbrados_salida)->pluck('m2');

        $chart2 = new ProduccionMachimbradoraChart2;
        $chart2->labels($labels)->options([
            'legend' => [
                'display' => false,
            ],
            'title' => [
                'display' => true,
                'text' => 'Fajas machimbradas',
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

        $chart2->dataset('m² de fajas machimbradas', 'bar', $data)
            ->color($colorArray)
            ->backgroundColor($colorArray);

        $chart3 = new ProduccionMachimbradoraChart3;

        $chart3->labels(collect($this->dias_m2)->pluck('fecha'));

        $chart3->dataset('Horas', 'line', collect($this->dias_m2)->pluck('horas'))
            ->color('#94a3b8')
            ->fill(false);

        $chart3->dataset('M2', 'line', collect($this->dias_m2)->pluck('m2'))
            ->color('#475569')
            ->fill(false);

        $chart3->options([
            'scales' => [
                'yAxes' => [
                    [
                        'id' => 'M2',
                        'type' => 'linear',
                        'position' => 'left',
                        'ticks' => [
                            'beginAtZero' => true,
                        ],
                    ],
                    [
                        'id' => 'Horas',
                        'type' => 'linear',
                        'position' => 'right',
                        'ticks' => [
                            'beginAtZero' => true,
                            'max' => 12
                        ],
                    ],
                ],

            ],
            'legend' => [
                'display' => true,
                'position' => 'bottom',
            ],
            'title' => [
                'display' => true,
                'text' => 'Producción por día',
                'fontSize' => 20,
            ],
        ]);

        return view('livewire.stadistics.produccion-machimbradora', [
            'chart1' => $chart1,
            'chart2' => $chart2,
            'chart3' => $chart3,
        ]);
    }
}
