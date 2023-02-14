<?php

namespace App\Http\Controllers;

use App\Models\Lot;
use App\Models\User;
use App\Models\Company;
use App\Models\TypeOfTask;
use Illuminate\Http\Request;
use PDF;

class LotPDFController extends Controller
{
    public function pdf(Request $request)
    {
        $type_of_task = $request->type_of_task ?? null;
        $type_of_task_name = TypeOfTask::find($request->type_of_task)->name ?? null;
        $sublots_availability = $request->sublots_availability ?? null;

        switch ($request->sublots_availability) {
            case 'all':
                $sublots_availability_text = 'Todos';
                break;
            case 'available':
                $sublots_availability_text = 'Disponibles';
                break;
            case 'unavailable':
                $sublots_availability_text = 'No disponibles';
                break;
            case 'partially':
                $sublots_availability_text = 'Parcialmente disponibles';
                break;
        };

        $fromDate = $request->fromDate;
        $toDate = $request->toDate;

        $filtros = [
            'Tipo de tarea' => $type_of_task_name ?? 'Todos',
            'Disponibilidad de sublotes' => $sublots_availability_text ?? 'Todos',
            'Fecha desde' => $fromDate ?? 'Todos',
            'Fecha hasta' => $toDate ?? 'Todos',
        ];

        $company_stats = $this->getCompanyStats();
        $report_title = 'Reporte de lotes';
        $stats = $this->getStats($type_of_task, $sublots_availability, $fromDate, $toDate);

        $pdf = PDF::loadView('livewire.lots.lots-pdf', [
            'company_stats' => $company_stats,
            'report_title' => $report_title,
            'stats' => $stats,
            'filtros' => $filtros,
        ]);

        return $pdf->stream('lotes.pdf');
    }

    public function getStats($type_of_task, $sublots_availability, $fromDate, $toDate)
    {
        $lots = Lot::orderBy('created_at', 'desc')->get();

        // Filter by type of task
        if ($type_of_task != '') {
            $lots = Lot::whereHas('task', function ($query) use ($type_of_task) {
                $query->where('type_of_task_id', $type_of_task);
            })->orderBy('created_at', 'desc')->get();
        }

        // Filter by sublots availability
        if ($sublots_availability != 'all') {
            $lots = $lots->filter(function ($lot, $key) use ($sublots_availability) {
                if ($sublots_availability == 'available') {
                    return $lot->sublots->where('available', true)->count() == $lot->sublots->count();
                } elseif ($sublots_availability == 'unavailable') {
                    return $lot->sublots->where('available', true)->count() == 0;
                } elseif ($sublots_availability == 'partially') {
                    return $lot->sublots->where('available', true)->count() > 0 && $lot->sublots->where('available', true)->count() < $lot->sublots->count();
                }
            });
        }

        // Filter by date
        if ($fromDate != '' && $toDate != '') {
            $lots = $lots->filter(function ($lot, $key) use ($fromDate, $toDate) {
                return $lot->created_at->between($fromDate, $toDate);
            });
        } elseif ($fromDate != '') {
            $lots = $lots->filter(function ($lot, $key) use ($fromDate) {
                return $lot->created_at >= $fromDate;
            });
        } elseif ($toDate != '') {
            $lots = $lots->filter(function ($lot, $toDate) {
                return $lot->created_at <= $toDate;
            });
        }

        $stats = [];

        foreach ($lots as $lot) {

            $sublots_availability = 0;

            if ($lot->sublots->where('available', true)->count() == 0) {
                $sublots_availability = 0;
            } elseif ($lot->sublots->where('available', true)->count() == $lot->sublots->count()) {
                $sublots_availability = 1;
            } else {
                $sublots_availability = 2;
            }

            $m2 = 0;
            foreach ($lot->sublots as $sublot) {
                $m2 += $sublot->initial_m2;
            }

            $stats[] = [
                'id' => $lot->id,
                'lot_code' => $lot->code,
                'task' => $lot->task->typeOfTask->name,
                'task_id' => $lot->task->id,
                'm2' => $m2 == 0 ? 'N/A' : $m2 . ' m2',
                'sublots_count' => $lot->sublots->count() . ' sublotes',
                'sublots_availability' => $sublots_availability,
                'created_at' => $lot->created_at->format('d-m-Y H:i'),
            ];
        }

        return $stats;
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
