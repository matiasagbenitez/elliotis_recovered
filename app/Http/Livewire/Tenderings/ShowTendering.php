<?php

namespace App\Http\Livewire\Tenderings;

use DateInterval;
use App\Models\User;
use Livewire\Component;
use App\Models\Tendering;
use Illuminate\Support\Facades\Date;
use App\Http\Services\TenderingService;

class ShowTendering extends Component
{
    public $tender, $hashes = [], $user_who_cancelled = '', $user_who_finished = '';
    protected $listeners = ['finishTendering' => 'finishTendering'];

    public $stats = [];
    public $products = [];

    public function mount(Tendering $tendering)
    {
        if ($tendering->cancelled_by) {
            $id = $tendering->cancelled_by;
            $this->user_who_cancelled = User::find($id)->name;
        } elseif ($tendering->finished_by) {
            $id = $tendering->finished_by;
            $this->user_who_finished = User::find($id)->name;
        }
        $this->tender = $tendering;
        $this->hashes = $tendering->hashes;
        $this->getStats();
    }

    public function getStats()
    {
        // Calculate remaining time
        $now = now();
        $end_date = Date::parse($this->tender->end_date);
        $remaining_time = $end_date->diff($now);

        if ($remaining_time->invert) {
            $remaining_time = $remaining_time->format('%d días, %h horas, %i minutos');
        } else {
            $remaining_time = 'La fecha de finalización ya ha pasado';
        }

        $total_suppliers = $this->tender->hashes->count();
        $total_hashes_seen = $this->tender->hashes->where('seen', true)->count();
        $total_hashes_answered = $this->tender->hashes->where('answered', true)->count();

        $suppliers_stats = $total_suppliers . ' proveedores contactados. ' . $total_hashes_seen . ' han visto la licitación, ' . $total_hashes_answered . ' han respondido.';

        $this->stats = [
            'id' => $this->tender->id,
            'start_date' => Date::parse($this->tender->start_date)->format('d-m-Y H:i'),
            'end_date' => Date::parse($this->tender->end_date)->format('d-m-Y H:i'),
            'remaining_time' => $remaining_time,
            'active' => $this->tender->is_active,
            'finished' => $this->tender->is_finished,
            'total_products' => $this->tender->products->sum('pivot.quantity') . ' productos',
            'suppliers_stats' => $suppliers_stats,
        ];

        foreach ($this->tender->products as $product) {

            $this->products[] = [
                'id' => $product->id,
                'name' => $product->name,
                'quantity' => $product->pivot->quantity,
            ];
        }

        // dd($this->products);
    }

    public function finishTendering(Tendering $tendering)
    {
        try {

            if ($tendering->hashes->where('answered', true)->count() == 0) {
                $this->emit('error', 'No se puede finalizar la licitación. Si la licitación no tiene ninguna oferta recibida, debe anularla desde la sección anterior.');
                return;
            }

            TenderingService::init($tendering);

            $tendering->update([
                'is_finished' => true,
                'finished_at' => now(),
                'finished_by' => auth()->user()->id,
            ]);
            $tendering->hashes()->update(['is_active' => false]);

            return redirect()->route('admin.tenderings.show-finished-tendering', $tendering);
        } catch (\Throwable $th) {
            $this->emit('error', 'Ha ocurrido un error al finalizar la licitación.');
        }
    }

    public function render()
    {
        return view('livewire.tenderings.show-tendering');
    }
}
