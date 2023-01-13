<?php

namespace App\Http\Livewire\PurchaseOrders;

use App\Models\User;
use Livewire\Component;
use App\Models\Purchase;
use App\Models\PurchaseOrder;
use Illuminate\Support\Facades\Date;

class ShowPurchaseOrder extends Component
{
    public $purchaseOrder, $purchase, $supplier_discriminates_iva, $type_of_purchase;
    public $user_who_cancelled = '';

    public $data = [];
    public $titles = [];
    public $stats = [];
    public $totals = [];

    public function mount(PurchaseOrder $purchaseOrder)
    {
        if ($purchaseOrder->cancelled_by) {
            $id = $purchaseOrder->cancelled_by;
            $this->user_who_cancelled = User::find($id)->name;
        }
        $this->purchaseOrder = $purchaseOrder;
        $this->supplier_discriminates_iva = $purchaseOrder->supplier->iva_condition->discriminate;
        $this->type_of_purchase = $purchaseOrder->type_of_purchase;

        $this->purchase = Purchase::where('supplier_order_id', $purchaseOrder->id)->first();

        $this->getData();
        $this->getTitles();
        $this->getStats();
        $this->getTotals();
    }

    public function getData()
    {
        $this->data = [
            'id' => $this->purchaseOrder->id,
            'supplier' => $this->purchaseOrder->supplier->business_name,
            'iva_condition' => $this->purchaseOrder->supplier->iva_condition->name,
            'discriminate' => $this->purchaseOrder->supplier->iva_condition->discriminate ? 'Discrimina IVA' : 'No discrimina IVA',
            'date' => Date::parse($this->purchaseOrder->registration_date)->format('d/m/Y'),
            'total_weight' => $this->purchaseOrder->total_weight . ' TN',
            'type_of_purchase' => $this->purchaseOrder->type_of_purchase == '1' ? 'Detallada' : 'Mixta',
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
        } elseif ($this->type_of_purchase == 2) {
            $this->titles = [
                'product' => 'Producto',
                'quantity' => 'Cantidad',
                'tn_total' => 'Toneladas (TN) aprox.',
                'tn_price' => $this->supplier_discriminates_iva ? 'Precio único TN' : 'Precio único TN + IVA',
                'subtotal' => 'Subtotal'
            ];
        }
    }

    public function getStats()
    {
        if ($this->supplier_discriminates_iva) {
            foreach ($this->purchaseOrder->products as $product) {

                $this->stats[] = [
                    'id' => $product->id,
                    'name' => $product->name,
                    'quantity' => $product->pivot->quantity,
                    'tn_total' => $product->pivot->tn_total,
                    'tn_price' => '$' . number_format($product->pivot->tn_price, 2, ',', '.'),
                    'subtotal' => '$' . number_format($product->pivot->subtotal, 2, ',', '.'),
                ];
            }
        } else {
            foreach ($this->purchaseOrder->products as $product) {
                $this->stats[] = [
                    'id' => $product->id,
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
            'subtotal' => '$' . number_format($this->purchaseOrder->subtotal, 2, ',', '.'),
            'iva' => '$' . number_format($this->purchaseOrder->iva, 2, ',', '.'),
            'total' => '$' . number_format($this->purchaseOrder->total, 2, ',', '.'),
        ];
    }

    public function render()
    {
        return view('livewire.purchase-orders.show-purchase-order');
    }
}
