<?php

namespace App\Http\Controllers;

use App\Models\Company;
use PDF;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class ProductPDFController extends Controller
{
    public $filters = [
        'search' => '',
        'product_name' => '',
        'measure' => '',
        'wood_type' => '',
        'stock_parameter' => '',
    ];

    public function pdf(Request $request)
    {
        $filters = [
            'search' => $request->search,
            'product_name' => $request->product_name,
            'measure' => $request->measure,
            'wood_type' => $request->wood_type,
            'stock_parameter' => $request->stock_parameter,
        ];
        $stats = $this->getStats($filters);
        $company_stats = $this->getCompanyStats();
        $report_title = 'Listado de productos';

        $pdf = PDF::loadView('livewire.products.pdf', [
            'stats' => $stats,
            'company_stats' => $company_stats,
            'report_title' => $report_title,
        ]);
        // return view('livewire.products.pdf', [
        //     'stats' => $stats,
        //     'company_stats' => $company_stats,
        //     'report_title' => $report_title,
        // ]);
        return $pdf->stream('productos.pdf');
    }

    public function getStats()
    {
        $stats = [];

        $products = Product::whereHas('productType.product_name', function ($query) {
            $query->where('name', 'LIKE', '%' . $this->filters['product_name'] . '%');
        })->whereHas('productType.measure', function ($query) {
            $query->where('name', 'LIKE', '%' . $this->filters['measure']  . '%');
        })->whereHas('woodType', function ($query) {
            $query->where('name', 'LIKE', '%' . $this->filters['wood_type']  . '%');
        })->when($this->filters['stock_parameter']  ?? null, function($query) {
            $query->whereRaw('real_stock ' . $this->filters['stock_parameter']  . ' minimum_stock');
        })->where('name', 'LIKE', '%' . $this->filters['search']  . '%')->orderBy('id', 'asc')->get();

        foreach ($products as $product) {
            $m2 = $product->m2 * $product->real_stock;
            $stock_level = $product->real_stock > $product->minimum_stock ? 'Stock normal' : 'Bajo stock';

            $stats[] = [
                'id' => $product->id,
                'name' => $product->name,
                'wood_type' => $product->woodType->name,
                'stock_level' => $stock_level,
                'stock' => $product->real_stock,
                'm2' => $m2 . ' m²',
            ];
        }

        return $stats;
    }

    public function getCompanyStats()
    {
        $company = Company::find(1);
        $company_stats = [
            'name' => $company->name,
            'slogan' => $company->slogan,
            'address' => $company->address,
            'phone' => $company->phone,
            'email' => $company->email,
            'cp' => $company->cp,
        ];

        return $company_stats;
    }
}
