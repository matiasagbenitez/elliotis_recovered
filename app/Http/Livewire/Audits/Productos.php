<?php

namespace App\Http\Livewire\Audits;

use App\Models\User;
use Livewire\Component;
use OwenIt\Auditing\Models\Audit;
use Illuminate\Support\Facades\Date;

class Productos extends Component
{
    public $audits;
    public $stats = [];

    public function mount()
    {
        $this->audits = Audit::where('auditable_type', 'App\Models\Product')->orderBy('created_at', 'desc')->get();
        $this->getStats();
    }

    public function getStats()
    {
        foreach ($this->audits as $audit) {
            $old_values = str_replace('"', '', json_encode($audit->old_values));
            $new_values = str_replace('"', '', json_encode($audit->new_values));
            $old_values = str_replace(',', ' ', $old_values);
            $new_values = str_replace(',', ', ', $new_values);

            $this->stats[] = [
                'id' => $audit->id,
                'user' => User::find($audit->user_id)->name,
                'created_at' => Date::parse($audit->created_at)->format('d/m/Y H:i:s'),
                'event' => $audit->event,
                'model' => $audit->auditable_type,
                'auditable_id' => $audit->auditable_id,
                'old_values' => $old_values,
                'new_values' => $new_values,
            ];
        }
    }

    public function render()
    {
        return view('livewire.audits.productos');
    }
}
