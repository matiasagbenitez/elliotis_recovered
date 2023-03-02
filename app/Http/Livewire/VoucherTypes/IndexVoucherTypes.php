<?php

namespace App\Http\Livewire\VoucherTypes;

use App\Models\VoucherTypes;
use Livewire\Component;
use Livewire\WithPagination;

class IndexVoucherTypes extends Component
{
    use WithPagination;
    public $search;

    protected $listeners = ['delete', 'refresh' => 'render'];

    public function delete($id)
    {
        try {
            $voucherType = VoucherTypes::find($id);
            $voucherType->delete();
            $this->render();
            $this->emit('success', 'El tipo de comprobante se ha eliminado con éxito.');
        } catch (\Throwable $th) {
            $this->emit('error', 'El tipo de comprobante no se ha podido eliminar.');
        }
    }

    public function render()
    {
        $voucher_types = VoucherTypes::paginate(10);

        return view('livewire.voucher-types.index-voucher-types', compact('voucher_types'));
    }
}
