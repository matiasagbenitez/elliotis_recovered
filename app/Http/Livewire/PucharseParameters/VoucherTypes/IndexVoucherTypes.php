<?php

namespace App\Http\Livewire\PucharseParameters\VoucherTypes;

use App\Models\VoucherTypes;
use Livewire\Component;

class IndexVoucherTypes extends Component
{
    public function render()
    {
        $voucher_types = VoucherTypes::all();
        return view('livewire.pucharse-parameters.voucher-types.index-voucher-types', compact('voucher_types'));
    }
}
