<?php

namespace App\Http\Controllers;

use PDF;
use App\Models\User;
use App\Models\Company;
use App\Models\SaleOrder;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;

class SaleOrderDetailPDFController extends Controller
{
    public function pdf(SaleOrder $saleOrder)
    {
        $client_discriminates_iva = $saleOrder->client->iva_condition->discriminate;
        $user_who_cancelled = $saleOrder->cancelled_by ? User::find($saleOrder->cancelled_by)->name : '';

        $company_stats = $this->getCompanyStats();
        $report_title = 'Detalle de orden de venta N° ' . $saleOrder->id;
        $data = $this->getData($saleOrder);
        $titles = $this->getTitles($client_discriminates_iva);
        $stats = $this->getStats($saleOrder, $client_discriminates_iva);
        $totals = $this->getTotals($saleOrder);

        // return view('livewire.purchase-orders.pdf', compact('client_discriminates_iva', 'company_stats', 'report_title', 'data', 'titles', 'stats', 'totals', 'user_who_cancelled', 'saleOrder'));

        $pdf = PDF::loadView('livewire.sale-orders.pdf', compact('client_discriminates_iva', 'company_stats', 'report_title', 'data', 'titles', 'stats', 'totals', 'user_who_cancelled', 'saleOrder'));
        $client = Str::slug($saleOrder->client->business_name);
        return $pdf->stream('orden-venta-' . $client . '.pdf');
    }

    public function getData($saleOrder)
    {
        $data = [
            'id' => $saleOrder->id,
            'client' => $saleOrder->client->business_name,
            'cuit' => $saleOrder->client->cuit,
            'iva_condition' => $saleOrder->client->iva_condition->name,
            'date' => Date::parse($saleOrder->registration_date)->format('d/m/Y'),
            'observations' => $saleOrder->observations ? $saleOrder->observations : 'No tiene',
        ];

        return $data;
    }

    public function getTitles($client_discriminates_iva)
    {
            $titles = [
                'product' => 'Producto',
                'm2_unitary' => 'M2 Unitario',
                'quantity' => 'Unidades',
                'm2_total' => 'M2 Total',
                'tn_price' => $client_discriminates_iva ? 'Precio M2' : 'Precio M2 (+ IVA)',
                'subtotal' => 'Subtotal'
            ];

        return $titles;
    }

    public function getStats($saleOrder, $client_discriminates_iva)
    {
        $stats = [];

        if ($client_discriminates_iva) {
            foreach ($saleOrder->products as $product) {
                $stats[] = [
                    'name' => $product->name,
                    'm2_unitary' => $product->pivot->m2_unitary,
                    'quantity' => $product->pivot->quantity,
                    'm2_total' => $product->pivot->m2_total,
                    'm2_price' => '$' . number_format($product->pivot->m2_price, 2, ',', '.'),
                    'subtotal' => '$' . number_format($product->pivot->subtotal, 2, ',', '.'),
                ];
            }
        } else {
            foreach ($saleOrder->products as $product) {
                $stats[] = [
                    'name' => $product->name,
                    'm2_unitary' => $product->pivot->m2_unitary,
                    'quantity' => $product->pivot->quantity,
                    'm2_total' => $product->pivot->m2_total,
                    'm2_price' => '$' . number_format($product->pivot->m2_price * 1.21, 2, ',', '.'),
                    'subtotal' => '$' . number_format($product->pivot->subtotal * 1.21, 2, ',', '.'),
                ];
            }
        }

        return $stats;
    }

    public function getTotals($saleOrder)
    {
        $totals = [
            'subtotal' => '$' . number_format($saleOrder->subtotal, 2, ',', '.'),
            'iva' => '$' . number_format($saleOrder->iva, 2, ',', '.'),
            'total' => '$' . number_format($saleOrder->total, 2, ',', '.'),
        ];

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
