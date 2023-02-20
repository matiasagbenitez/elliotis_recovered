<?php

namespace App\Http\Livewire\Dashboard;

use App\Charts\DashboardChart;
use App\Models\Phase;
use App\Models\User;
use App\Models\Product;
use Livewire\Component;
use App\Models\SaleOrder;
use App\Models\TypeOfTask;
use Illuminate\Support\Facades\Date;

class Dashboard extends Component
{
    public $tasksTypes, $taskTypesStats = [];
    public $saleOrders, $saleOrdersStats = [];
    public $products, $productsStats = [];

    public function mount()
    {
        $this->tasksTypes = TypeOfTask::all();
        $this->taskTypesStats = $this->getTaskTypesStats();
        $this->saleOrders = SaleOrder::where('is_active', true)->where('its_done', false)->get();
        $this->saleOrdersStats = $this->getSaleOrdersStats();

        $this->productsStats = $this->getProductsStats();
    }

    public function getTaskTypesStats()
    {
        $stats = [];

        foreach ($this->tasksTypes as $taskType) {

            $running_task = $taskType->tasks()->where('task_status_id', 1)->first();
            $running_task ? $task = $running_task : $task = $taskType->tasks()->latest()->first();

            $pendingProducts = [];
            $pendingProduction = Product::where('phase_id', $taskType->finalPhase->id)->where('phase_id', '!=', $taskType->initialPhase->id)->get();

            foreach ($pendingProduction as $product) {
                if ($product->necessary_stock != null && $product->necessary_stock > 0) {
                    $pendingProducts[] = [
                        'product_id' => $product->id,
                        'name' => $product->name,
                        'necessary_stock' => $product->necessary_stock,
                    ];
                }
            }

            $stats[] = [
                'id' => $taskType->id,
                'task_id' => $running_task ? $task->id : null,
                'name' => $taskType->name,
                'description' => $taskType->description,
                'icon' => $taskType->icon,
                'running_task' => $task ? ($task->task_status_id == 1 ? true : false) : null,
                'user' =>
                $task ?
                    ($task->task_status_id == 1
                        ?
                        User::find($task->started_by)->name
                        :
                        User::find($task->finished_by)->name
                    ) : null,
                'date' =>
                $task ?
                    ($task->task_status_id == 1
                        ?
                        Date::parse($task->started_at)->format('d/m/Y H:i')
                        :
                        Date::parse($task->finished_at)->format('d/m/Y H:i')
                    ) : null,
                'pendingProducts' => empty($pendingProducts) ? false : true,
            ];
        }

        return $stats;
    }

    public function getSaleOrdersStats()
    {
        $stats = [];

        foreach ($this->saleOrders as $saleOrder) {
            foreach ($saleOrder->products as $product) {
                if (isset($stats[$product->id])) {
                    $stats[$product->id]['quantity'] += $product->pivot->quantity;
                    $stats[$product->id]['m2_total'] += $product->pivot->m2_total;
                } else {
                    $stats[$product->id] = [
                        'name' => $product->name,
                        'quantity' => $product->pivot->quantity,
                        'm2_total' => $product->pivot->m2_total
                    ];
                }
            }
        }

        return $stats;
    }

    public function getProductsStats()
    {
        $products = Product::where('phase_id', '!=', 1)->get()->groupBy('phase_id');

        $stats = [];

        foreach ($products as $phase_id => $products) {

            $total_real_stock = 0;
            $m2_real_stock = 0;
            $total_necessary_stock = 0;
            $m2_necessary_stock = 0;
            $total = 0;

            foreach ($products as $product) {
                $total_real_stock += $product->real_stock;
                $m2_real_stock += ($product->real_stock * $product->m2);
                $total_necessary_stock += $product->necessary_stock;
                $m2_necessary_stock += ($product->necessary_stock * $product->m2);
            }

            $total = $m2_real_stock + $m2_necessary_stock;

            $stats[] = [
                'phase_name' => Phase::find($phase_id)->name,
                'total_real_stock' => $total_real_stock,
                'm2_real_stock' => $m2_real_stock,
                'total_necessary_stock' => $total_necessary_stock,
                'm2_necessary_stock' => $m2_necessary_stock,
                'total' => $m2_necessary_stock > 0 ? $total : 0,
                'percentage' => $total_necessary_stock > 0 ? round(($total_real_stock * 100) / ($total_necessary_stock + $total_real_stock), 2) : 100,
            ];
        }

        return $stats;
    }

    public function render()
    {
        $chart1 = new DashboardChart;

        $labels = collect($this->productsStats)->pluck('phase_name')->toArray();
        $data1 = collect($this->productsStats)->pluck('total');
        $data2 = collect($this->productsStats)->pluck('m2_real_stock');

        $chart1->labels($labels)->options([
            'legend' => [
                'display' => true,
            ],
            'scales' => [
                'xAxes' => [
                    [
                        'ticks' => [
                            'autoSkip' => false,
                        ],
                    ],
                ],
            ],
        ]);

        $chart1->dataset('Total requerido', 'bar', $data1)
            ->color('#9ca3af')
            ->backgroundcolor('#9ca3af');

        $chart1->dataset('M2 en stock', 'bar', $data2)
            ->color('#d1d5db')
            ->backgroundcolor('#d1d5db');



        return view('livewire.dashboard.dashboard', [
            'chart1' => $chart1,
        ]);
    }
}
