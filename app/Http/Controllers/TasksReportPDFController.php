<?php

namespace App\Http\Controllers;

use App\Http\Services\TimeService;
use App\Models\Task;
use App\Models\User;
use App\Models\Company;
use App\Models\TypeOfTask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use PDF;

class TasksReportPDFController extends Controller
{
    public function pdf(Request $request)
    {
        $category = $request->category != '' ? $request->category : null;
        $type_of_task_id = $request->type_of_task_id != '' ? $request->type_of_task_id : null;
        $from_date = $request->from_date != null ? $request->from_date : null;
        $to_date = $request->to_date != null ? $request->to_date : null;

        switch ($category) {
            case 'movement':
                $category_name = 'Movimiento';
                break;
            case 'transformation':
                $category_name = 'Transformación';
                break;
            case 'movement_transformation':
                $category_name = 'Movimiento y Transformación';
                break;
            default:
                $category_name = 'Todas';
                break;
        }

        $filtros = [
            'Categoría' => $category_name,
            'Tipo de tarea' => TypeOfTask::find($type_of_task_id)->name ?? 'Todos',
            'Fecha inicio desde' => $from_date ? Date::parse($from_date)->format('d/m/Y H:i') : 'Sin fecha',
            'Fecha fin hasta' => $to_date ? Date::parse($to_date)->format('d/m/Y H:i') : 'Sin fecha',
        ];

        $tasks = Task::where('task_status_id', '!=', 1)

            ->when($type_of_task_id, function ($query) use ($type_of_task_id) {
                return $query->where('type_of_task_id', $type_of_task_id);
            })

            ->when($category, function ($query) use ($category) {
                switch ($category) {
                    case 'movement':
                        return $query->whereHas('typeOfTask', function ($query) {
                            return $query->where('movement', true)->where('transformation', false);
                        });
                        break;
                    case 'transformation':
                        return $query->whereHas('typeOfTask', function ($query) {
                            return $query->where('transformation', true)->where('movement', false);
                        });
                        break;
                    case 'movement_transformation':
                        return $query->whereHas('typeOfTask', function ($query) {
                            return $query->where('movement', true)->where('transformation', true);
                        });
                        break;
                }
            })

            ->when($from_date, function ($query) use ($from_date) {
                return $query->whereDate('started_at', '>=', $from_date);
            })

            ->when($to_date, function ($query) use ($to_date) {
                return $query->whereDate('finished_at', '<=', $to_date);
            })


            ->orderBy('created_at', 'desc')
            ->get();

        $company_stats = $this->getCompanyStats();
        $report_title = 'Reporte de producción';
        $stats = $this->getStats($tasks);
        $stadistics = $this->getStadistics($tasks);

        $pdf = PDF::loadView('livewire.production-report.pdf', compact('tasks', 'company_stats', 'report_title', 'stats', 'stadistics', 'filtros'));
        return $pdf->stream('tasks.pdf');
    }

    public function getStats($tasks)
    {
        $stats = [];
        foreach ($tasks as $task)
        {
            $stats[] = [
                'task_id' => $task->id,
                'type_of_task' => $task->typeOfTask->name,
                'started_at' => Date::parse($task->started_at)->format('d/m/Y H:i'),
                'started_by' => User::find($task->started_by)->name,
                'finished_at' => Date::parse($task->finished_at)->format('d/m/Y H:i'),
                'finished_by' => User::find($task->finished_by)->name,
                'volume' => $task->lot->sublots->sum('initial_m2') == 0 ? 'N/A' : $task->lot->sublots->sum('initial_m2') . ' m2',
            ];
        }

        return $stats;
    }

    public function getStadistics($tasks)
    {
        // MT = Movement Tasks
        // TT = Transformation Tasks
        // MTT = Movement and Transformation Tasks

        $mt_count = 0; $tt_count = 0; $mtt_count = 0;                       // Cantidad de cada tipo de tarea
        $total_time_mt = 0; $total_time_tt = 0; $total_time_mtt = 0;        // Tiempo total de cada tipo de tarea
        $total_m2_mt = 0; $total_m2_tt = 0; $total_m2_mtt = 0;              // M2 de cada tipo de tarea
        $m2_per_hour_mt = 0; $m2_per_hour_tt = 0; $m2_per_hour_mtt = 0;     // M2 por hora de cada tipo de tarea
        $max_m2_mt = 0; $max_m2_tt = 0; $max_m2_mtt = 0;                    // Mayor cantidad de M2 de cada tipo de tarea

        foreach ($tasks as $task)
        {
            // TAREAS DE MOVIMIENTO
            if ($task->typeOfTask->movement && !$task->typeOfTask->transformation) {

                $mt_count++;
                $time_diff = strtotime($task->finished_at) - strtotime($task->started_at);
                $total_time_mt += $time_diff;
                $m2 = $task->lot->sublots->sum('initial_m2');

                if ($m2 > $max_m2_mt) {
                    $max_m2_mt_id = $task->id;
                    $max_m2_mt = $m2;
                }

                $total_m2_mt += $task->lot->sublots->sum('initial_m2');

                // TAREAS DE TRANSFORMACIÓN
            } elseif ($task->typeOfTask->transformation && !$task->typeOfTask->movement) {

                $tt_count++;
                $time_diff = strtotime($task->finished_at) - strtotime($task->started_at);
                $total_time_tt += $time_diff;
                $m2 = $task->lot->sublots->sum('initial_m2');

                if ($m2 > $max_m2_tt) {
                    $max_m2_tt_id = $task->id;
                    $max_m2_tt = $m2;
                }

                $total_m2_tt += $task->lot->sublots->sum('initial_m2');

                // TAREAS DE MOVIMIENTO Y TRANSFORMACIÓN
            } elseif ($task->typeOfTask->transformation && $task->typeOfTask->movement) {

                $mtt_count++;
                $time_diff = strtotime($task->finished_at) - strtotime($task->started_at);
                $total_time_mtt += $time_diff;
                $m2 = $task->lot->sublots->sum('initial_m2');

                if ($m2 > $max_m2_mtt) {
                    $max_m2_mtt_id = $task->id;
                    $max_m2_mtt = $m2;
                }

                $total_m2_mtt += $task->lot->sublots->sum('initial_m2');
            }
        }

        TimeService::secondsToHours($total_time_mt) == 0 ? $m2_per_hour_mt = 0 : $m2_per_hour_mt = $total_m2_mt / TimeService::secondsToHours($total_time_mt);
        TimeService::secondsToHours($total_time_tt) == 0 ? $m2_per_hour_tt = 0 : $m2_per_hour_tt = $total_m2_tt / TimeService::secondsToHours($total_time_tt);
        TimeService::secondsToHours($total_time_mtt) == 0 ? $m2_per_hour_mtt = 0 : $m2_per_hour_mtt = $total_m2_mtt / TimeService::secondsToHours($total_time_mtt);

        $stadistics = [
            'mt_count' => $mt_count,
            'tt_count' => $tt_count,
            'mtt_count' => $mtt_count,

            'total_time_mt' => TimeService::secondsToHoursAndMinutes($total_time_mt),
            'total_time_tt' => TimeService::secondsToHoursAndMinutes($total_time_tt),
            'total_time_mtt' => TimeService::secondsToHoursAndMinutes($total_time_mtt),

            'total_m2_mt' => number_format($total_m2_mt, 2, '.', ',') . ' m2',
            'total_m2_tt' => number_format($total_m2_tt, 2, '.', ',') . ' m2',
            'total_m2_mtt' => number_format($total_m2_mtt, 2, '.', ',') . ' m2',

            'm2_per_hour_mt' => number_format($m2_per_hour_mt, 2, '.', ',') . ' m2/h',
            'm2_per_hour_tt' => number_format($m2_per_hour_tt, 2, '.', ',') . ' m2/h',
            'm2_per_hour_mtt' => number_format($m2_per_hour_mtt, 2, '.', ',') . ' m2/h',

            'max_m2_mt' => number_format($max_m2_mt, 2, '.', ',') . ' m2',
            'max_m2_mt_id' => $max_m2_mt > 0 ? $max_m2_mt_id : null,
            'max_m2_mt_date' => $max_m2_mt > 0 ? Date::parse($tasks->find($max_m2_mt_id)->started_at)->format('d/m/Y H:i') : null,
            'max_m2_tt' => number_format($max_m2_tt, 2, '.', ',') . ' m2',
            'max_m2_tt_id' => $max_m2_tt > 0 ? $max_m2_tt_id : null,
            'max_m2_tt_date' => $max_m2_tt > 0 ? Date::parse($tasks->find($max_m2_tt_id)->started_at )->format('d/m/Y H:i') : null,
            'max_m2_mtt' => number_format($max_m2_mtt, 2, '.', ',') . ' m2',
            'max_m2_mtt_id' => $max_m2_mtt > 0 ? $max_m2_mtt_id : null,
            'max_m2_mtt_date' => $max_m2_mtt > 0 ? Date::parse($tasks->find($max_m2_mtt_id)->started_at)->format('d/m/Y H:i') : null,
        ];

        // dd($stadistics);
        return $stadistics;
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
