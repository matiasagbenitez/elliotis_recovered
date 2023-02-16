<?php

namespace App\Http\Controllers;

use PDF;
use DateTime;
use App\Models\Task;
use App\Models\User;
use App\Models\Sublot;
use App\Models\Company;
use Illuminate\Http\Request;
use App\Models\InputTaskDetail;
use App\Models\OutputTaskDetail;
use App\Http\Services\TimeService;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Crypt;
use App\Charts\ProduccionLineaCorteChart1;
use App\Charts\ProduccionLineaCorteChart2;

class ProduccionLineaCortePDFController extends Controller
{
    public function pdf(Request $request)
    {
        try {
            $filters = Crypt::decrypt($request->filters);

            $report_title = 'Producción en línea de corte';
            $report_subtitle = 'Desde el ' . date('d/m/Y H:i', strtotime($filters['from_datetime'])) . 'hs hasta el ' . date('d/m/Y H:i', strtotime($filters['to_datetime'])) . 'hs';

            $neutralColors = [
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

            $tareas_corte = Task::where('type_of_task_id', 2)
                ->where('cancelled', false)
                ->where('started_at', '>=', $filters['from_datetime'])
                ->where('finished_at', '<=', $filters['to_datetime'])
                ->get();

            // Total de rollos cortados
            $total_rollos = InputTaskDetail::whereIn('task_id', $tareas_corte->pluck('id'))->sum('consumed_quantity');

            // Total de tareas de corte
            $total_tareas_corte = $tareas_corte->count();

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

            // Cantidad de sublotes de rollos cortados
            $cantidad_sublotes_cortados = $sublotes_cortados->count();

            // Total de horas de corte
            $tiempo_corte = 0;
            foreach ($tareas_corte as $task) {
                $time_diff = strtotime($task->finished_at) - strtotime($task->started_at);
                $tiempo_corte += $time_diff;
            }
            $tiempo_corte_formateado = TimeService::secondsToHoursAndMinutes($tiempo_corte);

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

            // Cantidad de fajas cortadas
            $total_fajas_cortados = OutputTaskDetail::whereIn('task_id', $tareas_corte->pluck('id'))->sum('produced_quantity');

            // Total de m2 cortados
            $total_m2_cortados = OutputTaskDetail::whereIn('task_id', $tareas_corte->pluck('id'))->sum('m2');

            // Cantidad de sublotes de fajas cortadas
            $cantidad_sublotes_fajas_cortadas = $sublotes_fajas_cortadas->count();

            // M2 x hora
            $m2_x_hora = $tiempo_corte > 0 ? round($total_m2_cortados / TimeService::secondsToHours($tiempo_corte), 2) : 0;

            // M2 x rollo
            $m2_x_rollo = $total_rollos > 0 ? round($total_m2_cortados / $total_rollos, 2) : 0;

            // Rollos x hora
            $rollos_x_hora = $tiempo_corte > 0 ? round($total_rollos / TimeService::secondsToHours($tiempo_corte), 2) : 0;

            // Top 5 días con mayor producción
            $top_5_dias = [];
            $dias = [];
            foreach ($tareas_corte as $task) {
                $fecha = new DateTime($task->finished_at);
                $fecha = $fecha->format('Y-m-d');
                if (!isset($dias[$fecha])) {
                    $dias[$fecha] = [
                        'fecha' => Date::parse($fecha)->format('d/m/Y'),
                        'initial_m2' => $task->lot->sublots->sum('initial_m2')
                    ];
                } else {
                    $dias[$fecha]['initial_m2'] += $task->lot->sublots->sum('initial_m2');
                }
            }
            $dias = collect($dias);
            $dias = $dias->sortByDesc('initial_m2');
            $top_5_dias = $dias->take(5);

            $company_stats = $this->getCompanyStats();

            // GRAFICOS
            $labels = collect($productos_cortados)->pluck('nombre');
            $data = collect($productos_cortados)->pluck('cantidad_consumida');

            $chart1 = new ProduccionLineaCorteChart1;
            $chart1->labels($labels)->options([
                'legend' => [
                    'position' => 'top',
                    'labels' => [
                        'boxWidth' => 10,
                        'fontSize' => 13,
                    ],
                ],
            ]);
            $colors = range(1, count($labels));
            shuffle($colors);
            $colorArray = [];

            // Choose colors from $this->neutralColors without repeating
            foreach ($colors as $color) {
                // Not repeat colors from $this->neutralColors
                if (!in_array($neutralColors[$color], $colorArray)) {
                    $colorArray[] = $neutralColors[$color];
                }
            }

            $chart1->dataset('Cantidad de rollos cortadoss', 'bar', $data)
                ->backgroundColor($colorArray)
                ->color($colorArray);

            $labels = collect($productos_fajas_cortadas)->pluck('nombre');
            $data = collect($productos_fajas_cortadas)->pluck('m2_producidos');

            $chart2 = new ProduccionLineaCorteChart2;
            $chart2->labels($labels)->options([
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
                if (!in_array($neutralColors[$color], $colorArray)) {
                    $colorArray[] = $neutralColors[$color];
                }
            }

            $chart2->dataset('M2 cortados', 'pie', $data)
                ->color($colorArray)
                ->backgroundColor($colorArray);

                // return view('livewire.stadistics.produccion-linea-corte-pdf', compact(
                //     'company_stats',
                //     'report_title',
                //     'report_subtitle',
                //     'total_tareas_corte',
                //     'cantidad_sublotes_cortados',
                //     'total_rollos',
                //     'tiempo_corte_formateado',
                //     'rollos_x_hora',
                //     'productos_cortados',
                //     'total_fajas_cortados',
                //     'total_m2_cortados',
                //     'cantidad_sublotes_fajas_cortadas',
                //     'productos_fajas_cortadas',
                //     'm2_x_hora',
                //     'm2_x_rollo',
                //     'top_5_dias',
                //     'chart1',
                //     'chart2'
                // ));

            $pdf = PDF::loadView('livewire.stadistics.produccion-linea-corte-pdf', compact(
                'company_stats',
                'report_title',
                'report_subtitle',
                'total_tareas_corte',
                'cantidad_sublotes_cortados',
                'total_rollos',
                'tiempo_corte_formateado',
                'rollos_x_hora',
                'productos_cortados',
                'total_fajas_cortados',
                'total_m2_cortados',
                'cantidad_sublotes_fajas_cortadas',
                'productos_fajas_cortadas',
                'm2_x_hora',
                'm2_x_rollo',
                'top_5_dias',
                'chart1',
                'chart2'
            ));

            return $pdf->stream('produccion-linea-corte.pdf');

        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Error al generar el reporte',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function getCompanyStats()
    {
        $company = Company::find(1);
        $company_stats = [
            'name' => $company->name,
            'cuit' => $company->cuit,
            'slogan' => $company->slogan,
            'address' => $company->address,
            'phone' => $company->phone,
            'email' => $company->email,
            'cp' => $company->cp,
            'logo' => $company->logo,
            'date' => date('d/m/Y H:i'),
            'user' => User::find(auth()->user()->id)->name
        ];

        return $company_stats;
    }
}
