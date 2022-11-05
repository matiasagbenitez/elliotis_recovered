<?php

namespace App\Http\Livewire\Tenderings;

use App\Models\Product;
use Livewire\Component;
use App\Models\Supplier;
use App\Models\Tendering;
use Illuminate\Support\Facades\Mail;
use App\Mail\TenderingCreatedMailable;

class CreateTendering extends Component
{
    // PRODUCTS
    public $suppliers = [];
    public $orderProducts = [];
    public $allProducts = [];

    // CREATE FORM
    public $createForm = [
        'user_id' => 1,
        'start_date' => '',
        'end_date' => '',
        'subtotal' => 0,
        'iva' => 0,
        'total' => 0,
        'observations' => '',
    ];

    public function mount()
    {
        $this->suppliers = Supplier::where('active', true)->get();
        $this->allProducts = Product::where('is_buyable', true)->orderBy('name')->get();
        $this->orderProducts = [
            ['product_id' => '', 'quantity' => 1, 'price' => 0, 'subtotal' => '0']
        ];
    }

    public function addProduct()
    {
        if (count($this->orderProducts) == count($this->allProducts)) {
            return;
        }

        if (!empty($this->orderProducts[count($this->orderProducts) - 1]['product_id']) || count($this->orderProducts) == 0) {
            $this->orderProducts[] = ['product_id' => '', 'quantity' => 1, 'price' => 0, 'subtotal' => '0'];
        }
    }

    public function isProductInOrder($productId)
    {
        foreach ($this->orderProducts as $product) {
            if ($product['product_id'] == $productId) {
                return true;
            }
        }
        return false;
    }

    public function removeProduct($index)
    {
        unset($this->orderProducts[$index]);
        $this->orderProducts = array_values($this->orderProducts);
        $this->updatedOrderProducts();
    }

    public function updatedOrderProducts()
    {
        $subtotal = 0;

        foreach ($this->orderProducts as $index => $product) {
            $this->orderProducts[$index]['price'] = Product::find($product['product_id'])->cost ?? 0;
            $this->orderProducts[$index]['subtotal'] = number_format($product['quantity'] * $this->orderProducts[$index]['price'], 2, '.', '');
            $subtotal += $this->orderProducts[$index]['subtotal'];
        }

        $iva = $subtotal * 0.21;
        $total = $subtotal + $iva;

        $this->createForm['subtotal'] = number_format($subtotal, 2, '.', '');
        $this->createForm['iva'] = number_format($iva, 2, '.', '');
        $this->createForm['total'] = number_format($total, 2, '.', '');
    }

    public function save()
    {
        $this->validate([
            'createForm.start_date' => 'required|date',
            'createForm.end_date' => 'required|date',
            'createForm.subtotal' => 'required',
            'createForm.iva' => 'required',
            'createForm.total' => 'required',
            'orderProducts' => 'required|array|min:1',
            'orderProducts.*.product_id' => 'required|exists:products,id',
            'orderProducts.*.quantity' => 'required|numeric|min:1',
        ]);

        // End date must be greater than start date
        if ($this->createForm['end_date'] < $this->createForm['start_date']) {
            $this->emit('error', 'La fecha de fin debe ser mayor a la fecha de inicio');
            return;
        }

        // End date must be at least 1 day greater than start date
        if ($this->createForm['end_date'] <= date('Y-m-d', strtotime($this->createForm['start_date'] . ' + 1 day'))) {
            $this->emit('error', 'La fecha de fin debe ser, como mínimo, el día siguiente a la fecha de inicio.');
            return;
        }

        $tendering = Tendering::create($this->createForm);

        foreach ($this->orderProducts as $product) {
            $tendering->products()->attach($product['product_id'], [
                'quantity' => $product['quantity'],
                'price' => $product['price'],
                'subtotal' => $product['subtotal'],
            ]);
        }

        $subtotal = $tendering->products->sum(function ($product) {
            return $product->pivot->subtotal;
        });
        $iva = $subtotal * 0.21;
        $total = $subtotal + $iva;

        $tendering->update([
            'subtotal' => $subtotal,
            'iva' => $iva,
            'total' => $total,
        ]);

        // -------------------------- CREACIÓN DE HASHES ----------------------------- //

        // Create a unique hash on hashes table for each supplier, so they can access the tendering
        foreach ($this->suppliers as $supplier) {
            $supplier->hashes()->create([
                'tendering_id' => $tendering->id,
                'hash' => md5($supplier->id . $tendering->id . time()),
                'sent_at' => now(),
                'seen_at' => null,
                'answered' => false,
            ]);

            $hash = $supplier->hashes()->where('tendering_id', $tendering->id)->first();

            // Send email to supplier
            $mail = new TenderingCreatedMailable($supplier, $hash);
            Mail::to($supplier->email)->send($mail);
        }

        // -------------------------- FIN CREACIÓN DE HASHES ----------------------------- //

        // Retornamos el mensaje de éxito y redireccionamos a la vista de listado
        $id = $tendering->id;
        $message = 'El concurso se ha creado con éxito. Se ha enviado un correo electrónico a los proveedores. El número de concurso es el #' . $id;
        session()->flash('flash.banner', $message);
        return redirect()->route('admin.tenderings.index');
    }

    public function render()
    {
        return view('livewire.tenderings.create-tendering');
    }
}
