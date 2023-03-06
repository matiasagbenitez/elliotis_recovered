<?php

namespace App\Http\Livewire\Stadistics;

use App\Charts\ComprasChart;
use App\Charts\ComprasChart2;
use App\Models\Purchase;
use Illuminate\Support\Facades\Crypt;
use Livewire\Component;
use Illuminate\Support\Facades\Date;

class Compras extends Component
{
    public $filters, $from_datetime, $to_datetime;
    public $total_compras, $total_proveedores, $productos_comprados, $proveedores, $total_tn, $promedio_tn_compra;

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

            $purchases = Purchase::where('is_active', true)
            ->where('is_confirmed', true)
            ->where('created_at', '>=', $this->filters['from_datetime'])
            ->where('created_at', '<=', $this->filters['to_datetime'])
            ->get();

            if ($purchases->count() == 0) {
                $this->total_compras = 0;
                $this->total_proveedores = 0;
                $this->productos_comprados = [];
                $this->proveedores = [];
                $this->total_tn = 0;
                $this->promedio_tn_compra = 0;
                return;
            }

            $total_compras = $purchases->count();
            $this->total_compras = $total_compras;

            $total_proveedores = $purchases->unique('supplier_id')->count();
            $this->total_proveedores = $total_proveedores;

            $productos_comprados = [];
            foreach ($purchases as $purchase) {
                foreach ($purchase->products as $product) {
                    if (!array_key_exists($product->id, $productos_comprados)) {
                        $productos_comprados[$product->id] = [
                            'nombre' => $product->name,
                            'cantidad' => $product->pivot->quantity,
                            'tn' => $product->pivot->tn_total,
                            'subtotal' => $product->pivot->subtotal * 1.21,
                        ];
                    } else {
                        $productos_comprados[$product->id]['cantidad'] += $product->pivot->quantity;
                        $productos_comprados[$product->id]['tn'] += $product->pivot->tn_total;
                        $productos_comprados[$product->id]['subtotal'] +=( $product->pivot->subtotal * 1.21);
                    }
                }
            }
            // Sort descending by tn
            $this->productos_comprados  = collect($productos_comprados)->sortByDesc('tn')->values()->all();


            $total_tn = collect($productos_comprados)->sum('tn');
            $this->total_tn = $total_tn;

            $promedio_tn_compra = round($total_tn / $total_compras, 2);
            $this->promedio_tn_compra = $promedio_tn_compra;

            $proveedores = [];
            foreach ($purchases as $purchase) {
                if (!array_key_exists($purchase->supplier_id, $proveedores)) {
                    $proveedores[$purchase->supplier_id] = [
                        'nombre' => $purchase->supplier->business_name,
                        'compras' => 1,
                        'tn' => $purchase->total_weight,
                        'total' => $purchase->total,
                    ];
                } else {
                    $proveedores[$purchase->supplier_id]['compras'] += 1;
                    $proveedores[$purchase->supplier_id]['tn'] += $purchase->total_weight;
                    $proveedores[$purchase->supplier_id]['total'] += $purchase->total;
                }
            }
            $this->proveedores = $proveedores;

        } catch (\Throwable $th) {
            $this->emit('error', $th->getMessage());
        }
    }

    public function generatePDF()
    {
        try {
            $filters = Crypt::encrypt($this->filters);

            $stats = [
                'total_compras' => $this->total_compras,
                'total_proveedores' => $this->total_proveedores,
                'productos_comprados' => $this->productos_comprados,
                'proveedores' => $this->proveedores,
                'total_tn' => $this->total_tn,
                'promedio_tn_compra' => $this->promedio_tn_compra,
            ];

            $stats = Crypt::encrypt($stats);

            return redirect()->route('admin.compras.pdf', ['filters' => $filters, 'stats' => $stats]);
        } catch (\Throwable $th) {
            $this->emit('error', $th->getMessage());
        }
    }

    public function render()
    {
        $chart1 = new ComprasChart;
        $labels = collect($this->proveedores)->pluck('nombre');
        $data = collect($this->proveedores)->pluck('tn');

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
                'text' => 'Toneladas por Proveedor',
                'fontSize' => 20,
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

        $chart1->dataset('Toneladas', 'bar', $data)->backgroundColor($colorArray)->color($colorArray);



        $chart2 = new ComprasChart2;

        $labels = collect($this->productos_comprados)->pluck('nombre');
        $data = collect($this->productos_comprados)->pluck('tn');

        $chart2->labels($labels)->options([
            'legend' => [
                'display' => true,
                'position' => 'bottom',
            ],
            'title' => [
                'display' => true,
                'text' => 'Toneladas adquiridas por producto',
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

        $chart2->dataset('Toneladas', 'pie', $data)->backgroundColor($colorArray)->color($colorArray);

        return view('livewire.stadistics.compras', [
            'chart1' => $chart1,
            'chart2' => $chart2,
        ]);
    }
}
