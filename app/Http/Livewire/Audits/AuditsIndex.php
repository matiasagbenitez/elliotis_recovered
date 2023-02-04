<?php

namespace App\Http\Livewire\Audits;

use App\Models\User;
use Illuminate\Support\Facades\Date;
use Livewire\Component;
use OwenIt\Auditing\Models\Audit;

class AuditsIndex extends Component
{
    public $models;
    public $selected_model = '';
    public $audits;
    public $stats = [];

    public function mount()
    {
        // Get all audits
        $this->audits = Audit::all();
        $this->models = $this->getModels();
        $this->getStats();
    }

    public function updatedSelectedModel($value)
    {

        if ($value == '') {
            $this->audits = Audit::all();
            $this->getStats();
        } else {
            $this->audits = Audit::where('auditable_type', $this->selected_model)->get();
            $this->getStats();
        }
    }

    public function getStats()
    {
        foreach ($this->audits as $audit) {

            $old_values = str_replace('"', '', json_encode($audit->old_values));
            $new_values = str_replace('"', '', json_encode($audit->new_values));

            $this->stats[] = [
                'id' => $audit->id,
                'user' => User::find($audit->user_id)->name,
                'created_at' => Date::parse($audit->created_at)->format('d/m/Y H:i:s'),
                'event' => $audit->event,
                'model' => $audit->auditable_type,
                'old_values' => $old_values,
                'new_values' => $new_values,
            ];
        }

        // dd($this->stats);
    }

    public function getModels()
    {
        $models = [];
        $models = [
            [
                'name' => 'Mejores ofertas',
                'model' => 'App\Models\BestOffer',
            ],
            [
                'name' => 'Clientes',
                'model' => 'App\Models\Client',
            ],
            [
                'name' => 'Productos siguientes',
                'model' => 'App\Models\FollowingProduct',
            ],
            [
                'name' => 'Lotes',
                'model' => 'App\Models\Lot',
            ],
            [
                'name' => 'Productos anteriores',
                'model' => 'App\Models\PreviousProduct',
            ],
            [
                'name' => 'Compras',
                'model' => 'App\Models\Purchase',
            ],
            [
                'name' => 'Ordenes de compra',
                'model' => 'App\Models\PurchaseOrder',
            ],
            [
                'name' => 'Ventas',
                'model' => 'App\Models\Sale',
            ],
            [
                'name' => 'Ordenes de venta',
                'model' => 'App\Models\SaleOrder',
            ],
            [
                'name' => 'Sublotes',
                'model' => 'App\Models\Sublot',
            ],
            [
                'name' => 'Proveedores',
                'model' => 'App\Models\Supplier',
            ],
            [
                'name' => 'Tareas',
                'model' => 'App\Models\Task',
            ],
            [
                'name' => 'Licitaciones',
                'model' => 'App\Models\Tendering',
            ],
            [
                'name' => 'Usuarios',
                'model' => 'App\Models\User'
            ]
        ];

        // Order from A to Z by name
        usort($models, function($a, $b) {
            return $a['name'] <=> $b['name'];
        });

        return $models;
    }

    public function render()
    {
        return view('livewire.audits.audits-index');
    }
}
