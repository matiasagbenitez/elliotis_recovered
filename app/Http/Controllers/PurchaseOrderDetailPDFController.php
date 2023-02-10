<?php

namespace App\Http\Controllers;

use PDF;
use App\Models\User;
use App\Models\Company;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\PurchaseOrder;
use Illuminate\Support\Facades\Date;

class PurchaseOrderDetailPDFController extends Controller
{
    public function pdf(PurchaseOrder $purchaseOrder)
    {
        $supplier_discriminates_iva = $purchaseOrder->supplier->iva_condition->discriminate;
        $type_of_purchase = $purchaseOrder->type_of_purchase;
        $user_who_cancelled = $purchaseOrder->cancelled_by ? User::find($purchaseOrder->cancelled_by)->name : '';

        $company_stats = $this->getCompanyStats();
        $report_title = 'Detalle de orden de compra N° ' . $purchaseOrder->id;
        $data = $this->getData($purchaseOrder);
        $titles = $this->getTitles($type_of_purchase, $supplier_discriminates_iva);
        $stats = $this->getStats($purchaseOrder, $supplier_discriminates_iva);
        $totals = $this->getTotals($purchaseOrder);

        // return view('livewire.purchase-orders.pdf', compact('supplier_discriminates_iva', 'company_stats', 'report_title', 'data', 'titles', 'stats', 'totals', 'user_who_cancelled', 'purchaseOrder'));

        $pdf = PDF::loadView('livewire.purchase-orders.pdf', compact('supplier_discriminates_iva', 'company_stats', 'report_title', 'data', 'titles', 'stats', 'totals', 'user_who_cancelled', 'purchaseOrder'));
        $supplier = Str::slug($purchaseOrder->supplier->business_name);
        return $pdf->stream('detalle-orden-compra-' . $supplier . '.pdf');
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

    public function getStats($purchaseOrder, $supplier_discriminates_iva)
    {
        $stats = [];

        if ($supplier_discriminates_iva) {
            foreach ($purchaseOrder->products as $product) {
                $stats[] = [
                    'name' => $product->name,
                    'quantity' => $product->pivot->quantity,
                    'tn_total' => $product->pivot->tn_total,
                    'tn_price' => '$' . number_format($product->pivot->tn_price, 2, ',', '.'),
                    'subtotal' => '$' . number_format($product->pivot->subtotal, 2, ',', '.'),
                ];
            }
        } else {
            foreach ($purchaseOrder->products as $product) {
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

    public function getTotals($purchaseOrder)
    {
        $totals = [
            'subtotal' => '$' . number_format($purchaseOrder->subtotal, 2, ',', '.'),
            'iva' => '$' . number_format($purchaseOrder->iva, 2, ',', '.'),
            'total' => '$' . number_format($purchaseOrder->total, 2, ',', '.'),
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
