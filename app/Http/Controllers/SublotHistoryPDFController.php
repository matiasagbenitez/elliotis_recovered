<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use PDF;

class SublotHistoryPDFController extends Controller
{
    public function pdf(Request $request)
    {
        $sublotStats = Crypt::decrypt($request->sublotStats);
        $historic = Crypt::decrypt($request->historic);
        $report_title = 'Historial de Sublote';
        $report_subtitle = 'Sublote: ' . $sublotStats['code'];
        $company_stats = $this->getCompanyStats();

        $pdf = \PDF::loadView('livewire.sublots-tracking.pdf', compact('report_title', 'report_subtitle', 'sublotStats', 'historic', 'company_stats'));
        return $pdf->stream('sublot-tracking.pdf');
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
