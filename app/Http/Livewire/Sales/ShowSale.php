<?php

namespace App\Http\Livewire\Sales;

use App\Models\Sale;
use App\Models\User;
use Livewire\Component;

class ShowSale extends Component
{
    public $sale;
    public $user_who_cancelled = '';

    public function mount(Sale $sale)
    {
        if ($sale->cancelled_by) {
            $id = $sale->cancelled_by;
            $this->user_who_cancelled = User::find($id)->name;
        }
        $this->sale = $sale;
    }


    public function render()
    {
        return view('livewire.sales.show-sale');
    }
}
