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
        $stats = Crypt::decrypt($request->stats);
        // dd($stats);
        $report_title = 'Producción en machimbradora';
        $report_subtitle = 'Desde el ' . date('d/m/Y H:i', strtotime($filters['from_datetime'])) . 'hs hasta el ' . date('d/m/Y H:i', strtotime($filters['to_datetime'])) . 'hs';
        $company_stats = $this->getCompanyStats();

        $pdf = PDF::loadView('livewire.stadistics.produccion-machimbradora-pdf', compact(
            'report_title',
            'report_subtitle',
            'filters',
            'stats',
            'company_stats'
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
