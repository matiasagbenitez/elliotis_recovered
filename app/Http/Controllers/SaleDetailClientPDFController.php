<?php

namespace App\Http\Controllers;

use PDF;
use App\Models\Sale;
use App\Models\User;
use App\Models\Company;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;

class SaleDetailClientPDFController extends Controller
{
    public function pdf(Sale $sale)
    {
        $client_discriminates_iva = $sale->client->iva_condition->discriminate;
        $user_who_cancelled = $sale->cancelled_by ? User::find($sale->cancelled_by)->name : '';

        $company_stats = $this->getCompanyStats();
        $report_title = 'Detalle de venta N° ' . $sale->id;
        $data = $this->getData($sale);
        $titles = $this->getTitles($client_discriminates_iva);
        $stats = $this->getStats($sale, $client_discriminates_iva);
        $totals = $this->getTotals($sale);

        // return view('livewire.sales.detail-pdf', compact('client_discriminates_iva', 'company_stats', 'report_title', 'data', 'titles', 'stats', 'totals', 'user_who_cancelled', 'sale'));

        $pdf = PDF::loadView('livewire.sales.detail-client-pdf', compact('client_discriminates_iva', 'company_stats', 'report_title', 'data', 'titles', 'stats', 'totals', 'user_who_cancelled', 'sale'));
        $client = Str::slug($sale->client->business_name);
        return $pdf->stream('detalle-venta-' . $client . '.pdf');
    }

    public function getData($sale)
    {
        $data = [
            'id' => $sale->id,
            'client' => $sale->client->business_name,
            'cuit' => $sale->client->cuit,
            'iva_condition' => $sale->client->iva_condition->name,
            'discriminate' => $sale->client->iva_condition->discriminate ? 'Discrimina IVA' : 'No discrimina IVA',
            'date' => Date::parse($sale->registration_date)->format('d/m/Y'),

            'payment_method' => $sale->payment_method->name,
            'payment_condition' => $sale->payment_condition->name,
            'sale_order_id' => $sale->client_order_id ? $sale->client_order_id : 'No tiene',
            'voucher_type' => $sale->voucher_type->name,
            'voucher_number' => $sale->voucher_number,
            'observations' => $sale->observations ? $sale->observations : 'No tiene',
        ];

        return $data;
    }

    public function getTitles($client_discriminates_iva)
    {
        $titles = [
            'product' => 'Producto',
            'm2_unitary' => 'M2 unitario',
            'quantity' => 'Unidades',
            'm2_total' => 'M2 totales',
            'm2_price' => $client_discriminates_iva ? 'Precio M2' : 'Precio M2 (+ IVA)',
            'subtotal' => 'Subtotal'
        ];

        return $titles;
    }

    public function getStats($sale, $client_discriminates_iva)
    {
        $stats = [];
        $totals = [];

        foreach ($sale->products as $product) {
            $productId = $product->id;

            if (!isset($totals[$productId])) {
                $totals[$productId] = [
                    'product_id' => $product->id,
                    'name' => $product->name,
                    'quantity' => 0,
                    'm2_total' => 0,
                    'subtotal' => 0,
                ];
            }

            $m2_price = $client_discriminates_iva ? $product->pivot->m2_price : $product->pivot->m2_price * 1.21;

            $totals[$productId]['quantity'] += $product->pivot->quantity;
            $totals[$productId]['m2_total'] += $product->pivot->m2_total;
            $totals[$productId]['subtotal'] += $client_discriminates_iva ? $product->pivot->subtotal : $product->pivot->subtotal * 1.21;
            $totals[$productId]['m2_unitary'] = $product->pivot->m2_unitary;
            $totals[$productId]['m2_price'] = $m2_price;
        }

        $stats = array_values($totals);

        return $stats;
    }

    public function getTotals($sale)
    {
        $totals = [
            'subtotal' => '$' . number_format($sale->subtotal, 2, ',', '.'),
            'iva' => '$' . number_format($sale->iva, 2, ',', '.'),
            'total' => '$' . number_format($sale->total, 2, ',', '.'),
        ];

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
