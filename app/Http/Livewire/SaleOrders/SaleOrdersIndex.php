<?php

namespace App\Http\Livewire\SaleOrders;

use Livewire\Component;
use App\Models\SaleOrder;
use Livewire\WithPagination;

class SaleOrdersIndex extends Component
{
    use WithPagination;

    public $search;
    public $sort = 'id';
    public $direction = 'desc';

    protected $listeners = ['render', 'disable'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function order($sort)
    {
        if ($this->sort == $sort) {
            if ($this->direction == 'desc') {
                $this->direction = 'asc';
            } else {
                $this->direction = 'desc';
            }
        } else {
            $this->sort = $sort;
            $this->direction = 'desc';
        }
    }

    public function disable($id, $reason)
    {
        try {
            $saleOrder = SaleOrder::find($id);
            $saleOrder->is_active = false;
            $saleOrder->cancelled_by = auth()->user()->id;
            $saleOrder->cancelled_at = now();
            $saleOrder->cancel_reason = $reason;
            $saleOrder->save();
            $this->emit('success', 'Orden de venta desactivada correctamente.');
        } catch (\Exception $e) {
            $this->emit('error', 'Error al desactivar la orden de venta');
        }
    }

    public function render()
    {
        $saleOrders = SaleOrder::orderBy($this->sort, $this->direction)->paginate(10);
        return view('livewire.sale-orders.sale-orders-index', compact('saleOrders'));
    }
}
