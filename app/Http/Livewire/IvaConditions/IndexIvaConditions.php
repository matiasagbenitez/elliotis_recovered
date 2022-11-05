<?php

namespace App\Http\Livewire\IvaConditions;

use Livewire\Component;
use App\Models\IvaCondition;

class IndexIvaConditions extends Component
{
    protected $listeners = ['delete', 'refresh' => 'render'];

    public function delete(IvaCondition $ivaCondition)
    {
        try {
            $ivaCondition->delete();
            $this->emit('refresh');
            $this->emit('success', '¡Condición de IVA eliminada con éxito!');
        } catch (\Exception $e) {
            $this->emit('error', 'No puedes eliminar esta condición de IVA porque tiene clientes y/o proveedores asociados.');
        }
    }

    public function render()
    {
        $ivaConditions = IvaCondition::orderBy('updated_at', 'DESC')->get();
        return view('livewire.iva-conditions.index-iva-conditions', compact('ivaConditions'));
    }
}
