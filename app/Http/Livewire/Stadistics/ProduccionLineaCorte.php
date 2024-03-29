<?php

namespace App\Http\Livewire\Stadistics;

use DateTime;
use App\Models\Task;
use App\Models\Sublot;
use Livewire\Component;
use Termwind\Components\Dd;
use App\Models\InputTaskDetail;
use App\Models\OutputTaskDetail;
use App\Http\Services\TimeService;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Crypt;
use App\Charts\ProduccionLineaCorteChart1;
use App\Charts\ProduccionLineaCorteChart2;
use App\Charts\ProduccionLineaCorteChart3;

class ProduccionLineaCorte extends Component
{
    public $filters;
    public $from_datetime, $to_datetime;

    public $total_tareas_corte, $cantidad_sublotes_cortados, $total_rollos, $tiempo_corte_formateado, $rollos_x_hora, $productos_cortados;
    public $total_fajas_cortados, $total_m2_cortados, $cantidad_sublotes_fajas_cortadas, $productos_fajas_cortadas, $m2_x_hora, $m2_x_rollo;
    public $top_5_dias, $dias_m2;

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
            $tareas_corte = Task::where('type_of_task_id', 2)
                ->where('cancelled', false)
                ->where('started_at', '>=', $this->filters['from_datetime'])
                ->where('finished_at', '<=', $this->filters['to_datetime'])
                ->get();

            if ($tareas_corte->count() == 0) {
                $this->total_tareas_corte = 0;
                $this->cantidad_sublotes_cortados = 0;
                $this->total_rollos = 0;
                $this->tiempo_corte_formateado = 0;
                $this->rollos_x_hora = 0;
                $this->productos_cortados = [];
                $this->total_fajas_cortados = 0;
                $this->total_m2_cortados = 0;
                $this->cantidad_sublotes_fajas_cortadas = 0;
                $this->productos_fajas_cortadas = [];
                $this->m2_x_hora = 0;
                $this->m2_x_rollo = 0;
                $this->top_5_dias = [];
                return;
            }

            // Total de rollos cortados
            $total_rollos = InputTaskDetail::whereIn('task_id', $tareas_corte->pluck('id'))->sum('consumed_quantity');
            $this->total_rollos = $total_rollos;

            // Total de tareas de corte
            $total_tareas_corte = $tareas_corte->count();
            $this->total_tareas_corte = $total_tareas_corte;

            // Colección de sublotes de rollo cortados
            $sublotes_cortados = InputTaskDetail::whereIn('task_id', $tareas_corte->pluck('id'))->get();

            // Rollos cortados y cantidades
            $productos_cortados = [];
            foreach ($sublotes_cortados as $item) {
                $sublote = Sublot::find($item->sublot_id);
                $producto = $sublote->product;
                if (!isset($productos_cortados[$producto->id])) {
                    $productos_cortados[$producto->id] = [
                        'nombre' => $producto->name,
                        'cantidad_consumida' => $item->consumed_quantity,
                    ];
                } else {
                    $productos_cortados[$producto->id]['cantidad_consumida'] += $item->consumed_quantity;
                }
            }
            $this->productos_cortados = $productos_cortados;

            // Cantidad de sublotes de rollos cortados
            $cantidad_sublotes_cortados = $sublotes_cortados->count();
            $this->cantidad_sublotes_cortados = $cantidad_sublotes_cortados;

            // Total de horas de corte
            $tiempo_corte = 0;
            foreach ($tareas_corte as $task) {
                $time_diff = strtotime($task->finished_at) - strtotime($task->started_at);
                $tiempo_corte += $time_diff;
            }
            $tiempo_corte_formateado = TimeService::secondsToHoursAndMinutes($tiempo_corte);
            $this->tiempo_corte_formateado = $tiempo_corte_formateado;

            // Colección de sublotes de fajas cortadas
            $sublotes_fajas_cortadas = OutputTaskDetail::whereIn('task_id', $tareas_corte->pluck('id'))->get();

            // Fajas cortadas y cantidades
            $productos_fajas_cortadas = [];
            foreach ($sublotes_fajas_cortadas as $item) {
                $sublote = Sublot::find($item->sublot_id);
                $producto = $sublote->product;
                if (!isset($productos_fajas_cortadas[$producto->id])) {
                    $productos_fajas_cortadas[$producto->id] = [
                        'nombre' => $producto->name,
                        'cantidad_producida' => $item->produced_quantity,
                        'm2_producidos' => $item->m2
                    ];
                } else {
                    $productos_fajas_cortadas[$producto->id]['cantidad_producida'] += $item->produced_quantity;
                    $productos_fajas_cortadas[$producto->id]['m2_producidos'] += $item->m2;
                }
            }
            $this->productos_fajas_cortadas = $productos_fajas_cortadas;

            // Cantidad de fajas cortadas
            $total_fajas_cortados = OutputTaskDetail::whereIn('task_id', $tareas_corte->pluck('id'))->sum('produced_quantity');
            $this->total_fajas_cortados = $total_fajas_cortados;

            // Total de m2 cortados
            $total_m2_cortados = OutputTaskDetail::whereIn('task_id', $tareas_corte->pluck('id'))->sum('m2');
            $this->total_m2_cortados = $total_m2_cortados;

            // Cantidad de sublotes de fajas cortadas
            $cantidad_sublotes_fajas_cortadas = $sublotes_fajas_cortadas->count();
            $this->cantidad_sublotes_fajas_cortadas = $cantidad_sublotes_fajas_cortadas;

            // M2 x hora
            $m2_x_hora = $tiempo_corte > 0 ? round($total_m2_cortados / ($tiempo_corte / 3600)) : 0;
            $this->m2_x_hora = $m2_x_hora;

            // M2 x rollo
            $m2_x_rollo = $total_rollos > 0 ? round($total_m2_cortados / $total_rollos, 2) : 0;
            $this->m2_x_rollo = $m2_x_rollo;

            // Rollos x hora
            $rollos_x_hora = $tiempo_corte > 0 ? round($total_rollos / ($tiempo_corte / 3600)) : 0;
            $this->rollos_x_hora = $rollos_x_hora;

            // Top 5 días con mayor producción
            $top_5_dias = [];
            $dias = [];
            foreach ($tareas_corte as $task) {
                $fecha = Date::parse($task->started_at)->format('d/m/Y');
                if (!isset($dias[$fecha])) {
                    $dias[$fecha] = [
                        'fecha' => $fecha,
                        'initial_m2' => $task->lot->sublots->sum('initial_m2')
                    ];
                } else {
                    $dias[$fecha]['initial_m2'] += $task->lot->sublots->sum('initial_m2');
                }
            }
            $dias_m2 = $dias;
            $dias = collect($dias);
            $dias = $dias->sortByDesc('initial_m2');
            $top_5_dias = $dias->take(5);
            $this->top_5_dias = $top_5_dias;
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

            $stats = [
                'total_tareas_corte' => $this->total_tareas_corte,
                'cantidad_sublotes_cortados' => $this->cantidad_sublotes_cortados,
                'total_rollos' => $this->total_rollos,
                'tiempo_corte_formateado' => $this->tiempo_corte_formateado,
                'rollos_x_hora' => $this->rollos_x_hora,
                'productos_cortados' => $this->productos_cortados,
                'total_fajas_cortados' => $this->total_fajas_cortados,
                'total_m2_cortados' => $this->total_m2_cortados,
                'cantidad_sublotes_fajas_cortadas' => $this->cantidad_sublotes_fajas_cortadas,
                'productos_fajas_cortadas' => $this->productos_fajas_cortadas,
                'm2_x_hora' => $this->m2_x_hora,
                'm2_x_rollo' => $this->m2_x_rollo,
                'top_5_dias' => $this->top_5_dias,
            ];

            $stats_encrypted = Crypt::encrypt($stats);

            return redirect()->route('admin.produccion-linea-corte.pdf', [
                'filters' => $filters,
                'stats' => $stats_encrypted
            ]);

        } catch (\Throwable $th) {
            $this->emit('error', 'Error al generar la estadística. Revise los datos ingresados.');
        }
    }

    public function render()
    {
        $labels = collect($this->productos_cortados)->pluck('nombre');
        $data = collect($this->productos_cortados)->pluck('cantidad_consumida');

        $chart1 = new ProduccionLineaCorteChart1;
        $chart1->labels($labels)->options([
            'legend' => [
                'display' => false,
            ],
            'title' => [
                'display' => true,
                'text' => 'Rollos procesados',
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

        $chart1->dataset('Cantidad de rollos cortados', 'bar', $data)
            ->backgroundColor($colorArray)
            ->color($colorArray);

        $labels = collect($this->productos_fajas_cortadas)->pluck('nombre');
        $data = collect($this->productos_fajas_cortadas)->pluck('m2_producidos');

        $chart2 = new ProduccionLineaCorteChart2;
        $chart2->labels($labels)->options([
            'title' => [
                'display' => true,
                'text' => 'Fajas producidas',
                'fontSize' => 20,
            ],
            'legend' => [
                'position' => 'bottom',
                'labels' => [
                    'boxWidth' => 10,
                    'fontSize' => 13,
                ],
            ],
            'scales' => ['xAxes' => [['display' => false,],], 'yAxes' => [['display' => false,],],],
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

        $chart2->dataset('M2 cortados', 'pie', $data)
            ->color($colorArray)
            ->backgroundColor($colorArray);

        $chart3 = new ProduccionLineaCorteChart3;
        $chart3->labels(collect($this->dias_m2)->pluck('fecha'))->options([
            'title' => [
                'display' => true,
                'text' => 'M2 cortados por día',
                'fontSize' => 20,
            ],
            'legend' => [
                'display' => false,
            ],
        ]);

        $chart3->dataset('M2 cortados', 'line', collect($this->dias_m2)->pluck('initial_m2'))
            ->color('#475569')
            ->fill('#false');

        return view('livewire.stadistics.produccion-linea-corte', [
            'chart1' => $chart1,
            'chart2' => $chart2,
            'chart3' => $chart3,
        ]);
    }
}
