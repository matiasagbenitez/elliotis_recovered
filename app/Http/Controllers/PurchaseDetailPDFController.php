<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Company;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use PDF;

class PurchaseDetailPDFController extends Controller
{
    public function pdf(Purchase $purchase)
    {
        $supplier_discriminates_iva = $purchase->supplier->iva_condition->discriminate;
        $type_of_purchase = $purchase->type_of_purchase;
        $user_who_cancelled = $purchase->cancelled_by ? User::find($purchase->cancelled_by)->name : '';

        $company_stats = $this->getCompanyStats();
        $report_title = 'Detalle de compra N° ' . $purchase->id;
        $data = $this->getData($purchase);
        $titles = $this->getTitles($type_of_purchase, $supplier_discriminates_iva);
        $stats = $this->getStats($purchase, $supplier_discriminates_iva);
        $totals = $this->getTotals($purchase);

        // return view('livewire.purchases.pdf-detail', compact('supplier_discriminates_iva', 'company_stats', 'report_title', 'data', 'titles', 'stats', 'totals', 'user_who_cancelled', 'purchase'));

        $pdf = PDF::loadView('livewire.purchases.pdf-detail', compact('supplier_discriminates_iva', 'company_stats', 'report_title', 'data', 'titles', 'stats', 'totals', 'user_who_cancelled', 'purchase'));
        return $pdf->stream('detalle-compra.pdf');
    }

    public function getData($purchase)
    {
        $data = [
            'id' => $purchase->id,
            'supplier' => $purchase->supplier->business_name,
            'cuit' => $purchase->supplier->cuit,
            'iva_condition' => $purchase->supplier->iva_condition->name,
            'discriminate' => $purchase->supplier->iva_condition->discriminate ? 'Discrimina IVA' : 'No discrimina IVA',
            'date' => Date::parse($purchase->registration_date)->format('d/m/Y'),
            'total_weight' => $purchase->total_weight . ' TN',
            'type_of_purchase' => $purchase->type_of_purchase == '1' ? 'Detallada' : 'Mixta',
            'payment_method' => $purchase->payment_method->name,
            'payment_condition' => $purchase->payment_condition->name,
            'purchase_order_id' => $purchase->supplier_order_id ? $purchase->supplier_order_id : 'No tiene',
            'voucher_type' => $purchase->voucher_type->name,
            'voucher_number' => $purchase->voucher_number,
            'observations' => $purchase->observations ? $purchase->observations : 'No tiene',
        ];

        return $data;
    }

    public function getTitles($type_of_purchase, $supplier_discriminates_iva)
    {
        if ($type_of_purchase == 1) {
            $titles = [
                'product' => 'Producto',
                'quantity' => 'Cantidad',
                'tn_total' => 'Toneladas (TN)',
                'tn_price' => $supplier_discriminates_iva ? 'Precio TN' : 'Precio TN + IVA',
                'subtotal' => 'Subtotal'
            ];
        } else {
            $titles = [
                'product' => 'Producto',
                'quantity' => 'Cantidad',
                'tn_total' => 'Toneladas (TN) aprox.',
                'tn_price' => $supplier_discriminates_iva ? 'Precio único TN' : 'Precio único TN + IVA',
                'subtotal' => 'Subtotal',
            ];
        }

        return $titles;
    }

    public function getStats($purchase, $supplier_discriminates_iva)
    {
        $stats = [];

        if ($supplier_discriminates_iva) {
            foreach ($purchase->products as $product) {
                $stats[] = [
                    'name' => $product->name,
                    'quantity' => $product->pivot->quantity,
                    'tn_total' => $product->pivot->tn_total,
                    'tn_price' => '$' . number_format($product->pivot->tn_price, 2, ',', '.'),
                    'subtotal' => '$' . number_format($product->pivot->subtotal, 2, ',', '.'),
                ];
            }
        } else {
            foreach ($purchase->products as $product) {
                $stats[] = [
                    'name' => $product->name,
                    'quantity' => $product->pivot->quantity,
                    'tn_total' => $product->pivot->tn_total,
                    'tn_price' => '$' . number_format($product->pivot->tn_price * 1.21, 2, ',', '.'),
                    'subtotal' => '$' . number_format($product->pivot->subtotal * 1.21, 2, ',', '.'),
                ];
            }
        }

        return $stats;
    }

    public function getTotals($purchase)
    {
        $totals = [
            'subtotal' => '$' . number_format($purchase->subtotal, 2, ',', '.'),
            'iva' => '$' . number_format($purchase->iva, 2, ',', '.'),
            'total' => '$' . number_format($purchase->total, 2, ',', '.'),
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
