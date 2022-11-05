<?php

namespace App\Http\Livewire\IvaTypes;

use App\Models\IvaTypes;
use Livewire\Component;

class IndexIvaTypes extends Component
{
    protected $listeners = ['delete', 'refresh' => 'render'];

    public function delete(IvaTypes $ivaType)
    {
        $ivaType->delete();
    }

    public function render()
    {
        $iva_types = IvaTypes::orderBy('updated_at', 'DESC')->get();
        return view('livewire.iva-types.index-iva-types', compact('iva_types'));
    }
}
