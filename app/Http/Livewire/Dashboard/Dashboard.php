<?php

namespace App\Http\Livewire\Dashboard;

use App\Charts\CorteMachimbradoraChart;
use App\Charts\GeneralChart;
use App\Charts\GeneralesPieChart;
use App\Charts\HorasTrabajoChart;
use DateTime;
use App\Models\Task;
use Livewire\Component;
use App\Charts\LineaCorteChart;
use App\Models\InputTaskDetail;
use App\Models\OutputTaskDetail;
use App\Http\Services\EstadisticaService;
use Carbon\Carbon;
use ConsoleTVs\Charts\Classes\C3\Chart;

class Dashboard extends Component
{
    public $stats = [];
    public $filtros = [
        'fecha_inicio' => '',
        'fecha_fin' => '',
    ];

    public function mount()
    {
        // $this->stats = $this->getStats();
        $this->filtros['fecha_inicio'] = date('Y-m-d', strtotime('-1 week'));
        $this->filtros['fecha_fin'] = date('Y-m-d');
    }

    public function getStats()
    {

    }

    public function render()
    {
        $stats = EstadisticaService::estadisticasGenerales();
        $this->stats = $stats;

        // Linea corte
        $generalesPieChart = new GeneralesPieChart;
        $generalesPieChart->labels(['M² cortados', 'M² secados', 'M² machimbrados', 'M² empaquetados'])->options([
                'legend' => [
                    'display' => true,
                    'position' => 'bottom',
                    'labels' => [
                        'boxWidth' => 10,
                        'fontSize' => 13,
                    ],
                ],
                'scales' => ['xAxes' => [['display' => false,],], 'yAxes' => [['display' => false,],],],
            ]);
        $generalesPieChart->dataset('M2', 'doughnut', [$stats['total_m2_cortados'], $stats['total_m2_secados'], $stats['total_m2_machimbrados'], $stats['total_m2_empaquetados']])->options([
            'backgroundColor' => ['#cbd5e1', '#94a3b8', '#64748b', '#475569'],
        ]);
        $generalesPieChart->options([
            'legend' => [
                'position' => 'bottom',
            ],
        ]);

        // Linea corte vs Machimbradora
        $corteMachimbradoraChart = new CorteMachimbradoraChart;
        $corteMachimbradoraChart->labels(['M² cortados', 'M² machimbrados'])->options([
                'legend' => [
                    'display' => false,
                    'position' => 'bottom',
                    'labels' => [
                        'boxWidth' => 10,
                        'fontSize' => 13,
                    ],
                ],
                'scales' => ['xAxes' => [['display' => false,],], 'yAxes' => [['display' => false,],],],
            ]);
        $corteMachimbradoraChart->dataset('M2', 'pie', [$stats['total_m2_cortados'], $stats['total_m2_machimbrados']])->options([
            'backgroundColor' => ['#94a3b8', '#64748b'],
        ]);
        $generalesPieChart->options([
            'legend' => [
                'position' => 'bottom',
            ],
        ]);

        // Horas trabajo
        $horasTrabajoChart = new HorasTrabajoChart;
        $horasTrabajoChart->labels(['Horas corte', 'Horas machimbrado', 'Horas empaquetado'])->options([
                'legend' => [
                    'display' => true,
                    'position' => 'bottom',
                    'labels' => [
                        'boxWidth' => 10,
                        'fontSize' => 13,
                    ],
                ],
                'scales' => ['xAxes' => [['display' => false,],], 'yAxes' => [['display' => false,],],],
            ]);
        $horasTrabajoChart->dataset('Horas', 'polarArea', [$stats['horas_corte'], $stats['horas_machimbrado'], $stats['horas_empaquetado']])->options([
            'backgroundColor' => ['#cbd5e1', '#94a3b8', '#64748b'],
        ]);
        $generalesPieChart->options([
            'legend' => [
                'position' => 'bottom',
            ],
        ]);

        // Lineas
        $last_week = strtotime('-1 week');
        $tasks_corte = Task::where('type_of_task_id', 2)
            ->where('cancelled', false)
            ->where('started_at', '>=', date('Y-m-d H:i:s', $last_week))
            ->get();

        $tasks_corte_grouped = $tasks_corte->groupBy(function($item, $key) {
            return Carbon::parse($item->started_at)->format('Y-m-d');
        });

        return view('livewire.dashboard.dashboard', [
            'generalesPieChart' => $generalesPieChart,
            'corteMachimbradoraChart' => $corteMachimbradoraChart,
            'horasTrabajoChart' => $horasTrabajoChart,
            // 'chart' => $chart,
        ]);
    }
}
