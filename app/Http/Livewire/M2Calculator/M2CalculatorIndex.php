<?php

namespace App\Http\Livewire\M2Calculator;

use App\Models\Product;
use Livewire\Component;

class M2CalculatorIndex extends Component
{
    public $products = [], $required_m2 = 700;
    protected $listeners = ['calculate', 'resetCalculation'];

    public $products_quantities = [];
    public $products_quantities_stock = [];
    public $result, $total_price, $total_m2;

    public function mount()
    {
        $this->products = Product::where('is_salable', true)->get();
        $this->calculate();
    }

    public function calculate()
    {
        $this->products_quantities = [];
        foreach ($this->products as $product) {

            $quantity = $this->required_m2 / $product->m2;
            $quantity_10 = number_format($quantity * 1.1, 2, '.', '');
            $rounded_quantity_10 = round($quantity_10, 0, PHP_ROUND_HALF_UP);
            $m2_total = number_format($rounded_quantity_10 * $product->m2, 2, '.', '');
            $price = number_format($m2_total * $product->m2_price, 2, ',', '.');

            $this->products_quantities[] = [
                'product' => $product->name,
                'quantity_10' => '≈ ' . $quantity_10,
                'rounded_quantity_10' => $rounded_quantity_10 . ' paquetes',
                'm2_total' => $m2_total . ' m²',
                'best_option' => false,
                'price' => '$' . $price,
            ];
        }

        $best_option = collect($this->products_quantities)->sortBy('price')->first();
        $best_option['best_option'] = true;
        $this->products_quantities = collect($this->products_quantities)->map(function ($item) use ($best_option) {
            if ($item['product'] == $best_option['product']) {
                return $best_option;
            }
            return $item;
        })->toArray();

        $this->calculateWithStock();
    }

    public function calculateWithStock()
    {
        // Try to complete $required_m2 with stock products
        $this->products_quantities_stock = [];

        $products = Product::where('is_salable', true)->where('real_stock', '>', 0)->get();

        $quantities_m2 = [];
        foreach ($products as $product) {
            $quantities_m2[] = [
                'product_id' => $product->id,
                'unitary_m2' => $product->m2,
                'quantity' => $product->real_stock,
                'total_m2' => $product->real_stock * $product->m2,
            ];
        }

        $required_m2 = $this->required_m2 * 1.1;
        $stock_m2 = 0;
        $result = [];

        foreach ($quantities_m2 as $quantity_m2) {

            for ($i = 0; $i < $quantity_m2['quantity']; $i++) {

                $stock_m2 += $quantity_m2['unitary_m2'];

                if ($stock_m2 >= $required_m2) {
                    $result[] = [
                        'product_id' => $quantity_m2['product_id'],
                        'quantity' => 1,
                        'm2' => $quantity_m2['unitary_m2'],
                    ];
                    break;
                }

                $result[] = [
                    'product_id' => $quantity_m2['product_id'],
                    'quantity' => 1,
                    'm2' => $quantity_m2['unitary_m2'],
                ];
            }

            if ($stock_m2 >= $required_m2) {
                break;
            }

        }

        // Combinate products duplicates summing quantities
        $result = collect($result)->groupBy('product_id')->map(function ($item) {
            return [
                'product_id' => $item->first()['product_id'],
                'product_name' => Product::find($item->first()['product_id'])->name,
                'quantity' => '(' . $item->sum('quantity') . ' paquetes)',
                'm2' => $item->first()['m2'] * $item->sum('quantity'),
                'm2_formated' => number_format($item->first()['m2'] * $item->sum('quantity'), 2, '.', '') . ' m²',
                'price' => $item->first()['m2'] * $item->sum('quantity') * Product::find($item->first()['product_id'])->m2_price,
                'price_formated' => '$' . number_format($item->first()['m2'] * $item->sum('quantity') * Product::find($item->first()['product_id'])->m2_price, 2, ',', '.'),
            ];
        })->toArray();

        $this->total_price = '$' . number_format(collect($result)->sum('price'), 2, ',', '.');
        $this->total_m2 = number_format(collect($result)->sum('m2'), 2, '.', '') . ' m²';
        $this->result = $result;
    }

    public function resetCalculation()
    {
        $this->required_m2 = null;
    }

    public function render()
    {
        return view('livewire.m2-calculator.m2-calculator-index');
    }
}
