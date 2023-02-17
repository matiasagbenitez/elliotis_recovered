<?php

namespace App\Http\Controllers;

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
use PDF;

class ProduccionMachimbradoraPDFController extends Controller
{
    public function pdf(Request $request)
    {
        $filters = Crypt::decrypt($request->filters);

        $report_title = 'Producción en machimbradora';
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

        $tareas_machimbrado = Task::where('type_of_task_id', 6)
            ->where('cancelled', false)
            ->where('started_at', '>=', $filters['from_datetime'])
            ->where('finished_at', '<=', $filters['to_datetime'])
            ->get();

        // Si no hay tareas de machimbrado, no hay nada que calcular
        if ($tareas_machimbrado->count() == 0) {
            return;
        }

        // Total de tareas de machimbrado
        $total_tareas_machimbrado = $tareas_machimbrado->count();

        // Sublotes de entrada
        $sublotes_entrada = InputTaskDetail::whereIn('task_id', $tareas_machimbrado->pluck('id'))->get();
        $total_fajas_entrada = $sublotes_entrada->sum('consumed_quantity');
        $total_fajas_entrada_m2 = $sublotes_entrada->sum('m2');
        $cantidad_sublotes_entrada = $sublotes_entrada->count();

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

        // Horas de machimbrado
        $tiempo_machimbrado = 0;
        foreach ($tareas_machimbrado as $tarea) {
            $time_diff = strtotime($tarea->finished_at) - strtotime($tarea->started_at);
            $tiempo_machimbrado += $time_diff;
        }
        $tiempo_machimbrado_formateado = TimeService::secondsToHoursAndMinutes($tiempo_machimbrado);

        // Colección de sublotes de fajas machimbradas
        $sublotes_salida = OutputTaskDetail::whereIn('task_id', $tareas_machimbrado->pluck('id'))->get();
        $total_fajas_salida = $sublotes_salida->sum('produced_quantity');
        $total_fajas_salida_m2 = $sublotes_salida->sum('m2');
        $cantidad_sublotes_salida = $sublotes_salida->count();

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

        // M2 x hora
        $m2_x_hora = $tiempo_machimbrado > 0 ? round($total_fajas_salida_m2 / ($tiempo_machimbrado / 3600), 2) : 0;

        // Tasa de producción
        $tasa_produccion = $total_fajas_entrada > 0 ? round($total_fajas_salida / $total_fajas_entrada * 100, 2) : 0;

        // Tasa de producción m2
        $tasa_produccion_m2 = $total_fajas_entrada_m2 > 0 ? round($total_fajas_salida_m2 / $total_fajas_entrada_m2 * 100, 2) : 0;

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

        $company_stats = $this->getCompanyStats();

        $pdf = PDF::loadView('livewire.stadistics.produccion-machimbradora-pdf', compact(
            'company_stats',
            'report_title',
            'report_subtitle',

            'total_tareas_machimbrado',
            'total_fajas_entrada',
            'total_fajas_entrada_m2',
            'cantidad_sublotes_entrada',
            'productos_machimbrados_entrada',
            'tiempo_machimbrado_formateado',

            'total_fajas_salida',
            'total_fajas_salida_m2',
            'cantidad_sublotes_salida',
            'productos_machimbrados_salida',

            'm2_x_hora',
            'tasa_produccion',
            'top_5_dias',
        ));

        return $pdf->stream('produccion-machimbradora.pdf');
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
