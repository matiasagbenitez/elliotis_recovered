<?php

namespace App\Http\Controllers;

use PDF;
use App\Models\Lot;
use App\Models\User;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;

class LotDetailPDFController extends Controller
{
    public function pdf(Lot $lot)
    {
        $lot_code = $lot->code;

        $company_stats = $this->getCompanyStats();
        $report_title = 'Reporte de sublotes pertenecientes al lote ' . $lot->code;
        $lotStats = [
            'code' => $lot->code,
            'taskId' => $lot->task->id,
            'taskName' => $lot->task->typeOfTask->name,
            'startedBy' => User::find($lot->task->started_by)->name,
            'startedAt' => Date::parse($lot->task->started_at)->format('d-m-Y H:i'),
            'finishedBy' => User::find($lot->task->finished_by)->name,
            'finishedAt' => Date::parse($lot->task->finished_at)->format('d-m-Y H:i'),
            'sublots_count' => $lot->sublots->count(),
        ];

        $stats = $this->getStats($lot);

        $pdf = PDF::loadView('livewire.lots.lot-sublots-pdf', [
            'company_stats' => $company_stats,
            'lotStats' => $lotStats,
            'report_title' => $report_title,
            'stats' => $stats,
        ]);

        return $pdf->stream('lotes.pdf');
    }

    public function getStats($lot)
    {
        $stats = [];

        $sublots = $lot->sublots()->orderBy('created_at', 'desc')->get();

        foreach ($sublots as $sublot) {
            $stats[] = [
                'code' => $sublot->code,
                'product' => $sublot->product->name,
                'location' => $sublot->area->name,
                'initial_quantity' => $sublot->initial_quantity,
                'actual_quantity' => $sublot->actual_quantity > 0 ? $sublot->actual_quantity . 'm2' : '-',
                'initial_m2' => $sublot->initial_m2,
                'actual_m2' => $sublot->actual_m2 > 0 ? $sublot->actual_m2 . 'm2' : '-',
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
