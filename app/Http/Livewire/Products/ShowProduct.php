<?php

namespace App\Http\Livewire\Products;

use App\Models\Product;
use App\Models\Task;
use App\Models\TypeOfTask;
use Livewire\Component;

class ShowProduct extends Component
{
    public $product;
    public $stats = [];
    public $tasks_names = [];
    public $following_products = [];
    public $stock = [];
    public $sublots;

    public function mount(Product $product)
    {
        $this->product = $product;
        $this->sublots = $product->sublots->where('available', true);
        $this->getStats();
    }

    public function getStats()
    {
        $tasks = TypeOfTask::where('initial_phase_id', $this->product->phase_id)->orWhere('final_phase_id', $this->product->phase_id)->get();
        $tasks_count = $tasks->count();
        foreach ($tasks as $task) {
            $this->tasks_names[] = $task->name;
        }

        $following_products = $this->product->followingProducts;
        foreach ($following_products as $following_product) {
            $this->following_products[] = $following_product->name;
        }

        $previous_product = $this->product->previousProduct ? $this->product->previousProduct->name : null;

        $this->stats = [
            'created_at' => $this->product->created_at->format('d/m/Y'),
            'product_name' => $this->product->name,
            'category' => $this->product->productType->product_name->name,
            'measure' => $this->product->productType->measure->name,
            'unit' => $this->product->productType->unity->unities . ' (' . $this->product->productType->unity->name . ')',
            'product_phase' => $this->product->phase->name,
            'tasks_count' => $tasks_count,
            'previous_product' => $previous_product,
        ];

        $real_stock = $this->product->real_stock;
        $m2_stock = $this->product->real_stock * $this->product->m2;

        // Count of areas where are $this->product->sublots
        $sublots_stock = $this->product->sublots->where('available', true)->count();
        $areas = $this->product->sublots->where('available', true)->groupBy('area_id')->count();

        $this->stock = [
            'real_stock' => $real_stock,
            'm2_stock' => $m2_stock,
            'sublots_stock' => $sublots_stock,
            'areas' => $areas > 1 ? $areas . ' áreas' : $areas . ' área',
        ];
    }

    public function render()
    {
        return view('livewire.products.show-product');
    }
}
