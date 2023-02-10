<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\User;
use App\Models\Client;
use App\Models\Company;
use App\Models\VoucherTypes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use PDF;

class SalePDFController extends Controller
{
    public function pdf(Request $request)
    {
        $client = $request->client;
        $voucherType = $request->voucherType;
        $fromDate = $request->fromDate;
        $toDate = $request->toDate;

        $client_name = $client ? Client::find($client)->business_name : null;
        $voucher_type_name = $voucherType ? VoucherTypes::find($voucherType)->name : null;

        $filtros = [
            'Cliente' => $client_name ?? 'Todos',
            'Tipo de comprobante' => $voucher_type_name ?? 'Todos',
            'Desde' => $fromDate ?? 'Todos',
            'Hasta' => $toDate ?? 'Todos',
        ];

        $company_stats = $this->getCompanyStats();
        $report_title = 'Reporte de ventas';
        $stats = $this->getStats($client, $voucherType, $fromDate, $toDate);
        $totals = $this->getTotals($stats);

        $pdf = PDF::loadView('livewire.sales.pdf', [
            'company_stats' => $company_stats,
            'report_title' => $report_title,
            'stats' => $stats,
            'totals' => $totals,
            'filtros' => $filtros,
        ]);

        return $pdf->stream('ventas.pdf');
    }

    public function getStats($client, $voucherType, $fromDate, $toDate)
    {
        $stats = [];

        $sales = Sale::when($client ?? null, function ($query) use ($client) {
            $query->where('client_id', $client);
        })->when($voucherType ?? null, function ($query) use ($voucherType) {
            $query->where('voucher_type_id', $voucherType);
        })->when($fromDate ?? null, function ($query) use ($fromDate) {
            $query->where('date', '>=', $fromDate);
        })->when($toDate ?? null, function ($query) use ($toDate) {
            $query->where('date', '<=', $toDate);
        })->get();

        $total_sales = 0;
        $total_m2 = 0;

        foreach ($sales as $sale) {

            $total = $sale->total;
            $m2 = $sale->products->sum('pivot.m2_total');

            $stats[] = [
                'id' => $sale->id,
                'date' => Date::parse($sale->date)->format('d/m/Y'),
                'client' => $sale->client->business_name,
                'voucher_type' => $sale->voucher_type->name,
                'voucher_number' => $sale->voucher_number,
                'm2' => $m2,
                'm2_formated' => number_format($m2, 2, ',', '.') . ' m2',
                'total' => $total,
                'total_formated' => '$' . number_format($total, 2, ',', '.'),
            ];
        }

        return $stats;
    }

    public function getTotals($stats)
    {
        $totals = [
            'total_sales' => 0,
            'total_m2' => 0,
        ];

        foreach ($stats as $stat) {
            $totals['total_sales'] += $stat['total'];
            $totals['total_m2'] += $stat['m2'];
        }

        return $totals;
    }

    public function getCompanyStats()
    {
        $company = Company::find(1);
        $company_stats = [
            'name' => $company->name,
            'slogan' => $company->slogan,
            'address' => $company->address,
            'phone' => $company->phone,
            'email' => $company->email,
            'cp' => $company->cp,
            'date' => date('d/m/Y H:i'),
            'user' => User::find(auth()->user()->id)->name
        ];

        return $company_stats;
    }
}
