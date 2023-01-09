<?php

namespace App\Http\Livewire\NecesaryProduction;

use App\Http\Services\NecessaryProductionService;
use App\Models\SaleOrder;
use Livewire\Component;

class IndexNecessaryProduction extends Component
{
    public $saleOrders = [];
    public $resume = [];
    public $production = [];
    public $filter = '';

    public function mount()
    {
        $this->saleOrders = SaleOrder::where('is_active', true)->get();
        $this->resume = NecessaryProductionService::getResume();
        $this->production = NecessaryProductionService::calculate();
    }

    public function updatedFilter($value)
    {
        if ($value == '') {
            $this->resume = NecessaryProductionService::getResume();
            $this->production = NecessaryProductionService::calculate();
        } else {
            $order = SaleOrder::where('is_active', true)->where('id', $value)->first();
            $this->resume = NecessaryProductionService::getResume($order);
            $this->production = NecessaryProductionService::calculate($order);
        }
    }

    public function render()
    {
        return view('livewire.necesary-production.index-necessary-production');
    }
}
