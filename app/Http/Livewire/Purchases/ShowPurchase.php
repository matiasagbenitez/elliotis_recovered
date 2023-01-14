<?php

namespace App\Http\Livewire\Purchases;

use App\Models\User;
use Livewire\Component;
use App\Models\Purchase;
use Illuminate\Support\Facades\Date;

class ShowPurchase extends Component
{
    public $purchase, $supplier_discriminates_iva, $type_of_purchase;
    public $user_who_cancelled = '';

    public $data = [];
    public $titles = [];
    public $stats = [];
    public $totals = [];

    public function mount(Purchase $purchase)
    {
        if ($purchase->cancelled_by) {
            $id = $purchase->cancelled_by;
            $this->user_who_cancelled = User::find($id)->name;
        }

        $this->purchase = $purchase;
        $this->supplier_discriminates_iva = $purchase->supplier->iva_condition->discriminate;
        $this->type_of_purchase = $purchase->type_of_purchase;

        $this->getData();
        $this->getTitles();
        $this->getStats();
        $this->getTotals();
    }

    public function getData()
    {
        $this->data = [
            'id' => $this->purchase->id,
            'supplier' => $this->purchase->supplier->business_name,
            'iva_condition' => $this->purchase->supplier->iva_condition->name,
            'discriminate' => $this->purchase->supplier->iva_condition->discriminate ? 'Discrimina IVA' : 'No discrimina IVA',
            'date' => Date::parse($this->purchase->registration_date)->format('d/m/Y'),
            'total_weight' => $this->purchase->total_weight . ' TN',
            'type_of_purchase' => $this->purchase->type_of_purchase == '1' ? 'Detallada' : 'Mixta',
            'payment_method' => $this->purchase->payment_method->name,
            'payment_condition' => $this->purchase->payment_condition->name,
            'purchase_order_id' => $this->purchase->purchase_order_id ? $this->purchase->purchase_order_id : 'No tiene',
            'voucher_type' => $this->purchase->voucher_type->name,
            'voucher_number' => $this->purchase->voucher_number,
        ];
    }

    public function getTitles()
    {
        if ($this->type_of_purchase == 1) {
            $this->titles = [
                'product' => 'Producto',
                'quantity' => 'Cantidad',
                'tn_total' => 'Toneladas (TN)',
                'tn_price' => $this->supplier_discriminates_iva ? 'Precio TN' : 'Precio TN + IVA',
                'subtotal' => 'Subtotal'
            ];
        } else {
            $this->titles = [
                'product' => 'Producto',
                'quantity' => 'Cantidad',
                'tn_total' => 'Toneladas (TN) aprox.',
                'tn_price' => $this->supplier_discriminates_iva ? 'Precio único TN' : 'Precio único TN + IVA',
                'subtotal' => 'Subtotal',
            ];
        }
    }

    public function getStats()
    {

        if ($this->supplier_discriminates_iva) {
            foreach ($this->purchase->products as $product) {
                $this->stats[] = [
                    'name' => $product->name,
                    'quantity' => $product->pivot->quantity,
                    'tn_total' => $product->pivot->tn_total,
                    'tn_price' => '$' . number_format($product->pivot->tn_price, 2, ',', '.'),
                    'subtotal' => '$' . number_format($product->pivot->subtotal, 2, ',', '.'),
                ];
            }
        } else {
            foreach ($this->purchase->products as $product) {
                $this->stats[] = [
                    'name' => $product->name,
                    'quantity' => $product->pivot->quantity,
                    'tn_total' => $product->pivot->tn_total,
                    'tn_price' => '$' . number_format($product->pivot->tn_price * 1.21, 2, ',', '.'),
                    'subtotal' => '$' . number_format($product->pivot->subtotal * 1.21, 2, ',', '.'),
                ];
            }
        }
    }

    public function getTotals()
    {
        $this->totals = [
            'subtotal' => '$' . number_format($this->purchase->subtotal, 2, ',', '.'),
            'iva' => '$' . number_format($this->purchase->iva, 2, ',', '.'),
            'total' => '$' . number_format($this->purchase->total, 2, ',', '.'),
        ];
    }

    public function render()
    {
        return view('livewire.purchases.show-purchase');
    }
}
