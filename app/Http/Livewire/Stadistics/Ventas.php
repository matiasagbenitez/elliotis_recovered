<?php

namespace App\Http\Livewire\Stadistics;

use App\Charts\VentasChart;
use App\Charts\VentasChart2;
use App\Models\Sale;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Date;
use Livewire\Component;

class Ventas extends Component
{
    public $filters, $from_datetime, $to_datetime;
    public $total_ventas, $total_clientes, $productos_vendidos, $clientes, $productos_vendidos_ordenados, $total_m2, $promedio_m2_venta;
    public $neutralColors = [
        '#fafafa',
        '#f5f5f5',
        '#e5e5e5',
        '#d4d4d4',
        '#a3a3a3',
        '#737373',
        '#525252',
        '#404040',
        '#262626',
        '#171717',
    ];

    public function mount($filters)
    {
        $this->filters = $filters;
        $this->from_datetime = Date::parse($filters['from_datetime'])->format('Y-m-d H:i') . 'hs';
        $this->to_datetime = Date::parse($filters['to_datetime'])->format('Y-m-d H:i') . 'hs';
        $this->calculate();
    }

    public function calculate()
    {
        try {

            $sales = Sale::where('is_active', true)
            ->where('created_at', '>=', $this->filters['from_datetime'])
            ->where('created_at', '<=', $this->filters['to_datetime'])
            ->get();

            if ($sales->count() == 0) {
                $this->total_ventas = 0;
                $this->total_clientes = 0;
                $this->productos_vendidos = [];
                $this->clientes = [];
                $this->productos_vendidos_ordenados = [];
                return;
            }

            // Total de ventas
            $total_ventas = $sales->count();
            $this->total_ventas = $total_ventas;

            // Total de clientes
            $total_clientes = $sales->unique('client_id')->count();
            $this->total_clientes = $total_clientes;

            // Productos vendidos
            $productos_vendidos = [];
            foreach ($sales as $sale) {
                foreach ($sale->products as $product) {
                    if (!array_key_exists($product->id, $productos_vendidos)) {
                        $productos_vendidos[$product->id]['nombre'] = $product->name;
                        $productos_vendidos[$product->id]['unidades'] = $product->pivot->quantity;
                        $productos_vendidos[$product->id]['m2'] = $product->pivot->m2_total;
                        $productos_vendidos[$product->id]['total'] = $product->pivot->subtotal;
                    } else {
                        $productos_vendidos[$product->id]['unidades'] += $product->pivot->quantity;
                        $productos_vendidos[$product->id]['m2'] += $product->pivot->m2_total;
                        $productos_vendidos[$product->id]['total'] += $product->pivot->subtotal;
                    }
                }
            }
            $this->productos_vendidos = $productos_vendidos;

            $total_m2 = collect($productos_vendidos)->sum('m2');
            $this->total_m2 = $total_m2;

            $promedio_m2_venta = round($total_m2 / $total_ventas, 2);
            $this->promedio_m2_venta = $promedio_m2_venta;

            $clientes = [];
            foreach ($sales as $sale) {
                if (!array_key_exists($sale->client_id, $clientes)) {
                    $clientes[$sale->client_id]['nombre'] = $sale->client->business_name;
                    $clientes[$sale->client_id]['total'] = $sale->total;
                    $clientes[$sale->client_id]['cantidad'] = 1;
                } else {
                    $clientes[$sale->client_id]['total'] += $sale->total;
                    $clientes[$sale->client_id]['cantidad'] += 1;
                }
            }
            $this->clientes = $clientes;

            $this->productos_vendidos_ordenados = collect($productos_vendidos)->sortByDesc('m2')->values()->all();

        } catch (\Throwable $th) {
            $this->emit('error', $th->getMessage());
        }
    }

    public function generatePDF()
    {
        try {
            $filters = Crypt::encrypt($this->filters);

            $stats = [
                'total_ventas' => $this->total_ventas,
                'total_clientes' => $this->total_clientes,
                'productos_vendidos' => $this->productos_vendidos,
                'clientes' => $this->clientes,
                'productos_vendidos_ordenados' => $this->productos_vendidos_ordenados,
                'total_m2' => $this->total_m2,
                'promedio_m2_venta' => $this->promedio_m2_venta,
            ];

            $stats = Crypt::encrypt($stats);

            return redirect()->route('admin.ventas.pdf', ['filters' => $filters, 'stats' => $stats]);

        } catch (\Throwable $th) {
            $this->emit('error', $th->getMessage());
        }
    }

    public function render()
    {
        $chart1 = new VentasChart;

        $labels = collect($this->clientes)->pluck('nombre');
        $data = collect($this->clientes)->pluck('total');

        $chart1->labels($labels)->options([
            'legend' => [
                'display' => false,
                'position' => 'bottom',
                'labels' => [
                    'fontColor' => '#000',
                    'fontSize' => 14,
                ],
            ],
            'title' => [
                'display' => true,
                'text' => 'Monto total de ventas por cliente',
                'fontSize' => 16,
            ],
        ]);

        $colors = range(1, count($labels));
        shuffle($colors);
        $colorArray = [];

        // Choose colors from $this->neutralColors without repeating
        foreach ($colors as $color) {
            // Not repeat colors from $this->neutralColors
            if (!in_array($this->neutralColors[$color], $colorArray)) {
                $colorArray[] = $this->neutralColors[$color];
            }
        }

        $chart1->dataset('Ventas por cliente', 'bar', $data)
            ->backgroundColor($colorArray)
            ->color($colorArray);

        $chart2 = new VentasChart2;

        $labels = collect($this->productos_vendidos)->pluck('nombre');
        $data = collect($this->productos_vendidos)->pluck('m2');

        $chart2->labels($labels)->options([
            'legend' => [
                'display' => true,
                'position' => 'bottom',
            ],
            'title' => [
                'display' => true,
                'text' => 'Metros cuadrados vendidos por producto',
                'fontSize' => 16,
            ],
            'scales' => [
                'xAxes' => [
                    'display' => false,
                ],
                'yAxes' => [
                    'display' => false,
                ],
            ]
        ]);

        $chart2->dataset('Metros cuadrados vendidos por producto', 'pie', $data)
            ->backgroundColor($colorArray)
            ->color($colorArray);


        return view('livewire.stadistics.ventas', [
            'chart1' => $chart1,
            'chart2' => $chart2,
        ]);
    }
}
