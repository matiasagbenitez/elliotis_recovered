<?php

namespace App\Http\Livewire\Sales;

use App\Models\Sale;
use App\Models\User;
use App\Models\Sublot;
use Livewire\Component;

class ShowSaleClient extends Component
{
    public $sale, $client_discriminates_iva, $stats = [];
    public $user_who_cancelled = '';

    public function mount(Sale $sale)
    {
        if ($sale->cancelled_by) {
            $id = $sale->cancelled_by;
            $this->user_who_cancelled = User::find($id)->name;
        }

        $this->sale = $sale;
        $this->client_discriminates_iva = $sale->client->iva_condition->discriminate ? true : false;

        $this->getStats();
    }

    public function getStats()
    {
        $totals = [];

        foreach ($this->sale->products as $product) {
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

            $m2_price = $this->client_discriminates_iva ? $product->pivot->m2_price : $product->pivot->m2_price * 1.21;

            $totals[$productId]['quantity'] += $product->pivot->quantity;
            $totals[$productId]['m2_total'] += $product->pivot->m2_total;
            $totals[$productId]['subtotal'] += $this->client_discriminates_iva ? $product->pivot->subtotal : $product->pivot->subtotal * 1.21;
            $totals[$productId]['m2_unitary'] = $product->pivot->m2_unitary;
            $totals[$productId]['m2_price'] = $m2_price;
        }

        $this->stats = array_values($totals);

        // dd($this->stats);
    }

    public function render()
    {
        return view('livewire.sales.show-sale-client');
    }
}
