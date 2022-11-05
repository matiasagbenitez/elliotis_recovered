<?php

namespace App\Http\Livewire\Tenderings;

use Livewire\Component;
use App\Models\Tendering;
use Livewire\WithPagination;

class IndexTenderings extends Component
{
    use WithPagination;
    public $query, $direction = 'asc';

    protected $listeners = ['render', 'disable'];

    public function updatedQuery()
    {
        $this->resetPage();
    }

    public function disable($id, $reason)
    {
        try {
            $tendering = Tendering::find($id);
            $tendering->is_active = false;
            $tendering->cancelled_by = auth()->user()->id;
            $tendering->cancelled_at = now();
            $tendering->cancel_reason = $reason;
            $tendering->save();
            $this->emit('success', 'Concurso anulado correctamente.');
        } catch (\Exception $e) {
            $this->emit('error', 'Error al desactivar el concurso.');
        }
    }

    public function render()
    {
        switch ($this->query) {
            case 1:
                $tenderings = Tendering::where('is_active', true)->orderBy('id', $this->direction)->paginate(3);
                break;
            case 2:
                $tenderings = Tendering::where('is_active', false)->orderBy('id', $this->direction)->paginate(3);
                break;
            case 3:
                $tenderings = Tendering::where('is_active', true)->orderBy('end_date', 'asc')->paginate(3);
                break;
            case 4:
                $tenderings = Tendering::where('is_active', true)->orderBy('end_date', 'desc')->paginate(3);
                break;
            case 5:
                $tenderings = Tendering::where('is_analyzed', true)->orderBy('id', $this->direction)->paginate(3);
                break;
            case 6:
                $tenderings = Tendering::where('is_approved', true)->orderBy('id', $this->direction)->paginate(3);
                break;
            default:
                $tenderings = Tendering::orderBy('id', $this->direction)->paginate(3);
                break;
        }

        return view('livewire.tenderings.index-tenderings', compact('tenderings'));
    }
}
