<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class VentasPDFController extends Controller
{
    public function pdf(Request $request)
    {
        $filters = Crypt::decrypt($request->filters);
        $stats = Crypt::decrypt($request->stats);
        $report_title = 'Ventas';
        $report_subtitle = 'Desde el ' . date('d/m/Y H:i', strtotime($filters['from_datetime'])) . 'hs hasta el ' . date('d/m/Y H:i', strtotime($filters['to_datetime'])) . 'hs';
        $company_stats = $this->getCompanyStats();

        $pdf = \PDF::loadView('livewire.stadistics.ventas-pdf', compact('report_title', 'report_subtitle', 'filters', 'stats', 'company_stats'));
        return $pdf->stream('ventas.pdf');
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
