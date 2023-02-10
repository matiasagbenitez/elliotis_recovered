<?php

namespace App\Http\Controllers;

use App\Models\Company;
use PDF;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class ProductPDFController extends Controller
{

    public function pdf(Request $request)
    {
        $search = $request->search;
        $product_name = $request->product_name;
        $measure = $request->measure;
        $wood_type = $request->wood_type;
        $stock_parameter = $request->stock_parameter;

        $filtros = [
            'Busqueda por texto' => $search ?? 'Todos',
            'Clasificación producto' => $product_name ?? 'Todos',
            'Medida' => $measure ?? 'Todas',
            'Tipo de madera' => $wood_type ?? 'Todos',
            'Condición contra stock normal' => $stock_parameter ?? 'Todos',
        ];

        $company_stats = $this->getCompanyStats();
        $report_title = 'Reporte de productos';
        $stats = $this->getStats($search, $product_name, $measure, $wood_type, $stock_parameter);

        $pdf = PDF::loadView('livewire.products.pdf', [
            'company_stats' => $company_stats,
            'report_title' => $report_title,
            'stats' => $stats,
            'filtros' => $filtros,
        ]);

        // return view('livewire.products.pdf', [
        //     'stats' => $stats,
        //     'company_stats' => $company_stats,
        //     'report_title' => $report_title,
        // ]);

        return $pdf->stream('productos.pdf');
    }

    public function getStats($search, $product_name, $measure, $wood_type, $stock_parameter)
    {
        $stats = [];

        $products = Product::whereHas('productType.product_name', function ($query) use ($product_name) {
            $query->where('name', 'LIKE', '%' . $product_name . '%');
        })->whereHas('productType.measure', function ($query) use ($measure) {
            $query->where('name', 'LIKE', '%' . $measure . '%');
        })->whereHas('woodType', function ($query) use ($wood_type) {
            $query->where('name', 'LIKE', '%' . $wood_type . '%');
        })->when($stock_parameter  ?? null, function($query) use ($stock_parameter) {
            $query->whereRaw('real_stock ' . $stock_parameter  . ' minimum_stock');
        })->where('name', 'LIKE', '%' . $search . '%')->orderBy('id', 'asc')->get();

        foreach ($products as $product) {
            $m2 = $product->m2 * $product->real_stock;
            $stock_level = $product->real_stock > $product->minimum_stock ? 'Stock normal' : 'Stock bajo';

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
            'cuit' => $company->cuit,
            'slogan' => $company->slogan,
            'address' => $company->address,
            'phone' => $company->phone,
            'email' => $company->email,
            'cp' => $company->cp,
            'logo' => $company->logo,
            'date' => date('d/m/Y H:i'),
            'user' => User::find(auth()->user()->id)->name
        ];

        return $company_stats;
    }
}
