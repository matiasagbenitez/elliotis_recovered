<?php

namespace App\Http\Livewire\Purchases;

use App\Models\User;
use Livewire\Component;
use App\Models\Purchase;

class ShowPurchase extends Component
{
    public $purchase;
    public $user_who_cancelled = '';

    public function mount(Purchase $purchase)
    {
        if ($purchase->cancelled_by) {
            $id = $purchase->cancelled_by;
            $this->user_who_cancelled = User::find($id)->name;
        }
        $this->purchase = $purchase;
    }

    public function render()
    {
        return view('livewire.purchases.show-purchase');
    }
}
