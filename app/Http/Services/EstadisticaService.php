<?php

namespace App\Http\Services;

use DateTime;
use App\Models\Task;
use App\Models\InputTaskDetail;
use App\Models\OutputTaskDetail;
use App\Models\Product;
use DOTNET;

class EstadisticaService
{
    public static function estadisticasGenerales($fecha_inicio = null, $fecha_fin = null)
    {
        $last_week = strtotime('-1 week');
        $tasks_corte = Task::where('type_of_task_id', 2)
            ->where('cancelled', false)
            ->where('started_at', '>=', date('Y-m-d H:i:s', $last_week))
            ->get();

        $total_trunks = InputTaskDetail::whereIn('task_id', $tasks_corte->pluck('id'))->sum('consumed_quantity');
        $total_tareas_corte = $tasks_corte->count();

        $horas_corte = 0;
        foreach ($tasks_corte as $task) {
            $start = new DateTime($task->started_at);
            $end = new DateTime($task->finished_at);
            $interval = $start->diff($end);
            $horas_corte += $interval->h;
        }

        $total_cortados = OutputTaskDetail::whereIn('task_id', $tasks_corte->pluck('id'))->sum('produced_quantity');
        $total_m2_cortados = OutputTaskDetail::whereIn('task_id', $tasks_corte->pluck('id'))->sum('m2');


        $tasks_secado = Task::where('type_of_task_id', 4)
            ->where('cancelled', false)
            ->where('started_at', '>=', date('Y-m-d H:i:s', $last_week))
            ->get();
        $total_secados = OutputTaskDetail::whereIn('task_id', $tasks_secado->pluck('id'))->sum('produced_quantity');
        $total_m2_secados = OutputTaskDetail::whereIn('task_id', $tasks_secado->pluck('id'))->sum('m2');


        $tasks_machimbrado = Task::where('type_of_task_id', 6)
            ->where('cancelled', false)
            ->where('started_at', '>=', date('Y-m-d H:i:s', $last_week))
            ->get();

        $horas_machimbrado = 0;
        foreach ($tasks_machimbrado as $task) {
            $start = new DateTime($task->started_at);
            $end = new DateTime($task->finished_at);
            $interval = $start->diff($end);
            $horas_machimbrado += $interval->h;
        }
        $total_machimbrados = OutputTaskDetail::whereIn('task_id', $tasks_machimbrado->pluck('id'))->sum('produced_quantity');
        $total_m2_machimbrados = OutputTaskDetail::whereIn('task_id', $tasks_machimbrado->pluck('id'))->sum('m2');


        $tasks_empaquetado = Task::where('type_of_task_id', 9)
            ->where('cancelled', false)
            ->where('started_at', '>=', date('Y-m-d H:i:s', $last_week))
            ->get();

        $horas_empaquetado = 0;
        foreach ($tasks_empaquetado as $task) {
            $start = new DateTime($task->started_at);
            $end = new DateTime($task->finished_at);
            $interval = $start->diff($end);
            $horas_empaquetado += $interval->h;
        }

        $total_empaquetados = OutputTaskDetail::whereIn('task_id', $tasks_empaquetado->pluck('id'))->sum('produced_quantity');
        $total_m2_empaquetados = OutputTaskDetail::whereIn('task_id', $tasks_empaquetado->pluck('id'))->sum('m2');

        $tasks = Task::where('cancelled', false)
            ->where('started_at', '>=', date('Y-m-d H:i:s', $last_week))
            ->get();

        $horas = 0;
        foreach ($tasks as $task) {
            $start = new DateTime($task->started_at);
            $end = new DateTime($task->finished_at);
            $interval = $start->diff($end);
            $horas += $interval->h;
        }

        // STOCK
        $fajas_humedas = Product::where('phase_id', 2)->get();
        $m2_humedas =  $fajas_humedas->average('m2') * $fajas_humedas->sum('real_stock');

        $fajas_secas = Product::where('phase_id', 3)->get();
        $m2_secas =  $fajas_secas->average('m2') * $fajas_secas->sum('real_stock');

        $fajas_machimbradas = Product::where('phase_id', 4)->get();
        $m2_machimbradas =  $fajas_machimbradas->average('m2') * $fajas_machimbradas->sum('real_stock');

        $fajas_empaquetadas = Product::where('phase_id', 5)->get();
        $paquetes = $fajas_empaquetadas->sum('real_stock');
        $m2_empaquetadas =  $fajas_empaquetadas->average('m2') * $fajas_empaquetadas->sum('real_stock');

        return $stats = [

            // ROLLOS Y LÍNEA DE CORTE
            'total_trunks' => $total_trunks,
            'total_tareas_corte' => $total_tareas_corte,
            'horas_corte' => $horas_corte,
            'rollos_por_hora' => round($total_trunks / $horas_corte, 2),
            'total_cortados' => $total_cortados,
            'total_m2_cortados' => $total_m2_cortados,
            'cortes_por_hora' => round($total_m2_cortados / $horas_corte, 2),
            'm2_humedas' => $m2_humedas,

            // SECADOS
            'total_secados' => $total_secados,
            'total_m2_secados' => $total_m2_secados,
            'm2_secas' => $m2_secas,

            // MACHIMBRADOS
            'total_machimbrados' => $total_machimbrados,
            'horas_machimbrado' => $horas_machimbrado,
            'total_m2_machimbrados' => $total_m2_machimbrados,
            'machimbrados_por_hora' => round($total_m2_machimbrados / $horas_machimbrado, 2),
            'm2_machimbradas' => $m2_machimbradas,

            // EMPAQUETADOS
            'total_empaquetados' => $total_empaquetados,
            'total_m2_empaquetados' => $total_m2_empaquetados,
            'horas_empaquetado' => $horas_empaquetado,
            'empaquetados_por_hora' => round($total_m2_empaquetados / $horas_empaquetado, 2),
            'm2_empaquetadas' => $m2_empaquetadas,

            // TOTAL
            'horas' => $horas,

            // STOCK
            'fajas_humedas' => $fajas_humedas,
        ];
    }

    public static function estadisticasLinea($fecha_inicio = null, $fecha_fin = null)
    {
        $last_week = strtotime('-1 week');
        $tasks = Task::where('type_of_task_id', [2, 4, 6, 9])
            ->where('cancelled', false)
            ->where('started_at', '>=', date('Y-m-d H:i:s', $last_week))
            ->get();
    }
}
