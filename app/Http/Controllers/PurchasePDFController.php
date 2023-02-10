<?php

namespace App\Http\Controllers;
use PDF;
use App\Models\User;
use App\Models\Company;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\VoucherTypes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;

class PurchasePDFController extends Controller
{
    public function pdf(Request $request)
    {
        $supplier = $request->supplier;
        $voucherType = $request->voucherType;
        $fromDate = $request->fromDate;
        $toDate = $request->toDate;

        $supplier_name = $supplier ? Supplier::find($supplier)->business_name : null;
        $voucher_type_name = $voucherType ? VoucherTypes::find($voucherType)->name : null;

        $filtros = [
            'Proveedor' => $supplier_name ?? 'Todos',
            'Tipo de comprobante' => $voucher_type_name ?? 'Todos',
            'Desde' => $fromDate ?? 'Todos',
            'Hasta' => $toDate ?? 'Todos',
        ];

        $company_stats = $this->getCompanyStats();
        $report_title = 'Reporte de compras';
        $stats = $this->getStats($supplier, $voucherType, $fromDate, $toDate);
        $totals = $this->getTotals($stats);

        $pdf = PDF::loadView('livewire.purchases.pdf', [
            'company_stats' => $company_stats,
            'report_title' => $report_title,
            'stats' => $stats,
            'totals' => $totals,
            'filtros' => $filtros,
        ]);

        return $pdf->stream('compras.pdf');
    }

    public function getStats($supplier, $voucherType, $fromDate, $toDate)
    {
        $stats = [];

        $purchases = Purchase::when($supplier ?? null, function ($query) use ($supplier) {
            $query->where('supplier_id', $supplier);
        })->when($voucherType ?? null, function ($query) use ($voucherType) {
            $query->where('voucher_type_id', $voucherType);
        })->when($fromDate ?? null, function ($query) use ($fromDate) {
            $query->where('date', '>=', $fromDate);
        })->when($toDate ?? null, function ($query) use ($toDate) {
            $query->where('date', '<=', $toDate);
        })->get();

        foreach ($purchases as $purchase) {
            $stats[] = [
                'id' => $purchase->id,
                'date' => Date::parse($purchase->date)->format('d/m/Y'),
                'supplier' => $purchase->supplier->business_name,
                'voucher_type' => $purchase->voucher_type->name,
                'voucher_number' => $purchase->voucher_number,
                'total_weight' => $purchase->total_weight,
                'total_weight_formated' => $purchase->total_weight . 'TN',
                'total' => $purchase->total,
                'total_formated' => '$' . number_format($purchase->total, 2, ',', '.'),
            ];
        }

        return $stats;
    }

    public function getTotals($stats)
    {
        $total_weight = 0;
        $total = 0;

        foreach ($stats as $stat) {
            $total_weight += $stat['total_weight'];
            $total += $stat['total'];
        }

        return [
            'total_weight' => $total_weight,
            'total' => $total,
        ];
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
