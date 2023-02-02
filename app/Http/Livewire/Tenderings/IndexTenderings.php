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
    protected $listeners = ['disable' => 'disable'];

    public function updatedQuery()
    {
        $this->resetPage();
    }

    public function mount()
    {
        //
    }

    public function disable($id, $reason)
    {
        try {
            $tendering = Tendering::find($id);
            $tendering->update([
                'is_active' => false,
                'is_finished' => true,
                'is_cancelled' => true,
                'cancelled_by' => auth()->user()->id,
                'cancelled_at' => now(),
                'cancel_reason' => $reason,
            ]);

            $tendering->hashes()->update([
                'is_active' => false,
            ]);


            $tendering->hashes()->each(function ($hash) {
                Mail::to($hash->supplier->email)->send(new TenderingDeletedMailable($hash->supplier, $hash->tendering));
            });

            $this->emit('success', 'Concurso anulado correctamente.');
        } catch (\Exception $e) {
            $this->emit('error', 'Ha ocurrido un error al anular el concurso.');
        }
    }

    public function render()
    {
        $tenderings = Tendering::paginate(6);

        return view('livewire.tenderings.index-tenderings', compact('tenderings'));
    }
}
