<?php

namespace App\Http\Livewire\NecesaryProduction;

use App\Http\Services\NecessaryProductionService;
use App\Models\Product;
use App\Models\SaleOrder;
use Livewire\Component;

class IndexNecessaryProduction extends Component
{
    public $saleOrders = [];
    public $resume = [];
    public $production = [];
    public $productionFormated = [];
    public $filter = '';

    public function mount()
    {
        $this->saleOrders = SaleOrder::where('is_active', true)->get();
        $this->resume = NecessaryProductionService::getResume();
        $this->production = NecessaryProductionService::calculate();
        $this->getProductionFormated();
    }

    public function updatedFilter($value)
    {
        if ($value == '') {
            $this->resume = NecessaryProductionService::getResume();
            $this->production = NecessaryProductionService::calculate();
            $this->getProductionFormated();
        } else {
            $order = SaleOrder::where('is_active', true)->where('id', $value)->first();
            $this->resume = NecessaryProductionService::getResume($order);
            $this->production = NecessaryProductionService::calculate($order);
            $this->getProductionFormated();
        }
    }

    public function getProductionFormated()
    {
        $this->productionFormated = [];

        foreach ($this->production as $key => $value) {

            $m2 = Product::find($value['product_id'])->m2 * $value['quantity'];
            $m2_needed = Product::find($value['product_id'])->m2 * $value['needed'];

            $this->productionFormated[] = [
                'product_id' => $value['product_id'],
                'name' => $value['name'],
                'quantity' => $value['quantity'],
                'quantity_needed' => $value['needed'] > 0 ? $value['needed'] : '-',
                'm2' => $m2 . ' m2',
                'm2_needed' => $m2_needed > 0 ? $m2_needed . ' m2' : '-',
            ];

        }
    }

    public function render()
    {
        return view('livewire.necesary-production.index-necessary-production');
    }
}
