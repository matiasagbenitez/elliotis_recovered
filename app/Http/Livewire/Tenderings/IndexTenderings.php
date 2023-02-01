<?php

namespace App\Http\Livewire\Tenderings;

use App\Http\Services\TenderingService;
use Livewire\Component;
use App\Models\Tendering;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Mail;
use App\Mail\TenderingCreatedMailable;
use App\Mail\TenderingDeletedMailable;
use Termwind\Components\Dd;

class IndexTenderings extends Component
{
    use WithPagination;

    public $query, $direction = 'asc';

    public function updatedQuery()
    {
        $this->resetPage();
    }

    public function mount()
    {
        $tenderings = Tendering::where('is_active', true)->get();
    }

    public function disable($id, $reason)
    {
        try {
            $tendering = Tendering::find($id);
            $tendering->is_active = false;
            $tendering->cancelled_by = auth()->user()->id;
            $tendering->cancelled_at = now();
            $tendering->cancel_reason = $reason;

            $tendering->hashes()->update([
                'is_active' => false,
            ]);

            $tendering->save();

            $tendering->hashes()->each(function ($hash) {
                Mail::to($hash->supplier->email)->send(new TenderingDeletedMailable($hash->supplier, $hash->tendering));
            });

            $this->emit('success', 'Concurso anulado correctamente.');
        } catch (\Exception $e) {
            // $this->emit('error', 'Error al desactivar el concurso.');
            $this->emit('error', $e);
        }
    }

    public function render()
    {
        $tenderings = Tendering::where('is_active', true)->paginate(6);

        return view('livewire.tenderings.index-tenderings', compact('tenderings'));
    }
}
