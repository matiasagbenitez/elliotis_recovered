<?php

namespace App\Http\Livewire\Sales;

use App\Models\Sale;
use App\Models\User;
use Livewire\Component;

class ShowSale extends Component
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
        foreach ($this->sale->products as $product) {
            $this->stats[] = [
                'name' => $product->name,
                'm2_unitary' => $product->pivot->m2_unitary,
                'quantity' => $product->pivot->quantity,
                'm2_total' => $product->pivot->m2_total,
                'm2_price' => $this->client_discriminates_iva ? $product->pivot->m2_price : $product->pivot->m2_price * 1.21,
                'subtotal' => $this->client_discriminates_iva ? $product->pivot->subtotal : $product->pivot->subtotal * 1.21,
            ];
        }
    }


    public function render()
    {
        return view('livewire.sales.show-sale');
    }
}
