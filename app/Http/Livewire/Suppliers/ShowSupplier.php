<?php

namespace App\Http\Livewire\Suppliers;

use Livewire\Component;

class ShowSupplier extends Component
{
    public $supplier;

    public $isOpen = false;

    public function mount($supplier)
    {
        $this->supplier = $supplier;
    }

    public function showSupplier()
    {
        $this->isOpen = true;
    }

    public function render()
    {
        return view('livewire.suppliers.show-supplier');
    }
}
