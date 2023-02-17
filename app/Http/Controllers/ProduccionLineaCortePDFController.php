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
            $stats = Crypt::decrypt($request->stats);

            $report_title = 'Producción en línea de corte';
            $report_subtitle = 'Desde el ' . date('d/m/Y H:i', strtotime($filters['from_datetime'])) . 'hs hasta el ' . date('d/m/Y H:i', strtotime($filters['to_datetime'])) . 'hs';
            $company_stats = $this->getCompanyStats();


            $pdf = PDF::loadView('livewire.stadistics.produccion-linea-corte-pdf', compact(
                'report_title',
                'report_subtitle',
                'filters',
                'stats',
                'company_stats'
            ));

            return $pdf->stream('produccion-linea-corte.pdf');

        } catch (\Throwable $th) {
            abort(500);
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
