<?php

namespace App\Http\Livewire\PurchaseOrders;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\PurchaseOrder;

class PurchaseOrdersIndex extends Component
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
            $purchaseOrder = PurchaseOrder::find($id);
            $purchaseOrder->is_active = false;
            $purchaseOrder->cancelled_by = auth()->user()->id;
            $purchaseOrder->cancelled_at = now();
            $purchaseOrder->cancel_reason = $reason;
            $purchaseOrder->save();
            $this->emit('success', 'Orden de compra desactivada correctamente.');
        } catch (\Exception $e) {
            $this->emit('error', 'Error al desactivar la orden de compra');
        }
    }

    public function render()
    {
        $purchaseOrders = PurchaseOrder::orderBy($this->sort, $this->direction)->paginate(10);
        return view('livewire.purchase-orders.purchase-orders-index', compact('purchaseOrders'));
    }
}
