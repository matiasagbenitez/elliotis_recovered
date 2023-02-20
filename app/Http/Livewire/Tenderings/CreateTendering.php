<?php

namespace App\Http\Livewire\Tenderings;

use App\Models\Product;
use Livewire\Component;
use App\Models\Supplier;
use App\Models\Tendering;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Mail;
use App\Mail\TenderingCreatedMailable;
use App\Models\Purchase;

class CreateTendering extends Component
{
    public $allProducts = [];
    public $orderProducts = [];
    public $suppliers = [];
    public $tn_price, $notification = null;

    // CREATE FORM
    public $createForm = [
        'user_id' => '',
        'start_date' => '',
        'end_date' => '',
        'observations' => '',
    ];

    protected $validationAttributes = [
        'createForm.start_date' => 'fecha de inicio',
        'createForm.end_date' => 'fecha de fin',
        'createForm.observations' => 'observaciones',
    ];

    public function mount($notification = null)
    {
        $this->suppliers = Supplier::where('active', true)->get();
        $this->allProducts = Product::where('is_buyable', true)->orderBy('name')->get();
        $this->notification = auth()->user()->notifications->where('id', $notification)->first();

        if ($this->notification) {
            foreach ($this->notification->data['detail'] as $detail) {
                $this->orderProducts[] = [
                    'product_id' => $detail['product_id'],
                    'quantity' => $detail['reposition'],
                ];
            }
        } else {
            $this->orderProducts = [
                ['product_id' => '', 'quantity' => 1],
            ];
        }

        $lastPurchase = Purchase::where('type_of_purchase', 2)->orderBy('id', 'desc')->first();

        if ($lastPurchase) {
            $this->tn_price = $lastPurchase->products->first()->pivot->tn_price;
        } else {
            $this->tn_price = 0;
        }

        // set default start date to today (datetime local input)
        $this->createForm['start_date'] = date('Y-m-d\TH:i');
        $this->createForm['end_date'] = date('Y-m-d\TH:i', strtotime('+3 day'));
        $this->createForm['user_id'] = auth()->user()->id;
    }

    public function addProduct()
    {
        if (count($this->orderProducts) == count($this->allProducts)) {
            return;
        }

        if (!empty($this->orderProducts[count($this->orderProducts) - 1]['product_id']) || count($this->orderProducts) == 0) {
            $this->orderProducts[] = ['product_id' => '', 'quantity' => 1];
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

    public function save()
    {
        $this->createForm['user_id'] = auth()->user()->id;
        $this->notification ? $this->createForm['notification_id'] = $this->notification->id : null;

        $this->validate([
            'createForm.start_date' => 'required|date',
            'createForm.end_date' => 'required|date',
            'orderProducts' => 'required|array|min:1',
            'orderProducts.*.product_id' => 'required|exists:products,id',
            'orderProducts.*.quantity' => 'required|numeric|min:1',
        ]);

        // Validaciones de fechas
        if ($this->createForm['end_date'] < $this->createForm['start_date']) {
            $this->emit('error', 'La fecha de fin debe ser mayor a la fecha de inicio');
            return;
        }

        if ($this->createForm['end_date'] <= date('Y-m-d', strtotime($this->createForm['start_date'] . ' + 1 day'))) {
            $this->emit('error', 'La fecha de fin debe ser, como mínimo, el día siguiente a la fecha de inicio.');
            return;
        }

        $tendering = Tendering::create($this->createForm);

        foreach ($this->orderProducts as $product) {
            $tendering->products()->attach($product['product_id'], [
                'quantity' => $product['quantity'],
            ]);
        }

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
        $message = 'La licitación se ha creado con éxito. Se ha enviado un correo electrónico a los proveedores. El identificador de la licitación es el #' . $id . '.';
        session()->flash('flash.banner', $message);
        return redirect()->route('admin.tenderings.show-detail', $tendering);
    }

    public function render()
    {
        return view('livewire.tenderings.create-tendering');
    }
}
