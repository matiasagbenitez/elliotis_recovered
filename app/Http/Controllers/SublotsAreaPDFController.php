<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\User;
use App\Models\Sublot;
use App\Models\Company;
use Illuminate\Http\Request;
use PDF;

class SublotsAreaPDFController extends Controller
{
    public function pdf(Request $request)
    {
        $area_id = $request->area;

        $filtros = [
            'Area' => $area_id ? Area::find($area_id)->name : 'Todas las áreas',
        ];

        $company_stats = $this->getCompanyStats();
        $report_title = 'Reporte de sublotes en ' . $filtros['Area'];
        $stats = $this->getStats($area_id);
        $totals = $this->getTotals($stats);

        $pdf = PDF::loadView('livewire.lots.sublots-areas-pdf', [
            'company_stats' => $company_stats,
            'report_title' => $report_title,
            'stats' => $stats,
            'totals' => $totals,
            'filtros' => $filtros,
        ]);

        return $pdf->stream('sublotes-por-area.pdf');

    }

    public function getStats($area_id)
    {
        $stats = [];

        $sublots = Sublot::where('available', true)->when($area_id ?? null, function ($query) use ($area_id) {
            $query->where('area_id', $area_id);
        })->get();

        foreach ($sublots as $sublot) {
            $stats[] = [
                'id' => $sublot->id,
                'code' => $sublot->code,
                'product' => $sublot->product->name,
                'area' => $sublot->area->name,
                'actual_quantity' => $sublot->actual_quantity,
                'actual_m2' => $sublot->actual_m2 > 0 ? $sublot->actual_m2 : 0,
                'actual_m2_formated' => $sublot->actual_m2 > 0 ? $sublot->actual_m2 . ' m2' : 'N/A',
            ];
        }

        return $stats;
    }

    public function getTotals($stats)
    {
        $totals = [
            'unities' => 0,
            'm2' => 0,
        ];

        foreach ($stats as $stat) {
            $totals['unities'] += $stat['actual_quantity'];
            $totals['m2'] += $stat['actual_m2'];
        }

        return $totals;
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
