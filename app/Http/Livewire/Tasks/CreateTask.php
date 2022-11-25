<?php

namespace App\Http\Livewire\Tasks;

use App\Models\Task;
use Livewire\Component;
use App\Models\TaskType;
use App\Models\TrunkLot;

class CreateTask extends Component
{
    public $taskTypes;

    // public $allInputProducts = [];
    public $taskInputProducts = [];
    public $allInputProducts = [];

    public $createForm = [
        'task_type_id' => '',
        'task_status_id' => 2,
        'started_at' => '',
        'started_by' => 1,
        'finished_at' => '',
        'finished_by' => 1,
    ];

    public function mount()
    {
        $this->taskTypes = TaskType::all();
        // $this->updatedCreateFormTaskTypeId(1);
    }

    // SELECCIONAR TIPO DE TAREA Y CARGAR PRODUCTOS
    public function updatedCreateFormTaskTypeId($id)
    {
        switch ($id) {
            case 1:
                $this->allInputProducts = TrunkLot::where('available', true)->orderBy('id', 'asc')->get();
                $this->taskInputProducts = [
                    ['trunk_lot_id' => '', 'consumed_quantity' => 1]
                ];
                break;

            default:
                $this->reset('allInputProducts', 'taskInputProducts');
                break;
        }
    }

    // RESET TASK INPUT PRODUCTS
    public function resetTaskInputProducts()
    {
        $this->taskInputProducts = [
            ['trunk_lot_id' => '', 'consumed_quantity' => 1]
        ];
    }

    // ADD PRODUCT
    public function addProduct()
    {
        if (count($this->taskInputProducts) == count($this->allInputProducts)) {
            return;
        }

        if (!empty($this->taskInputProducts[count($this->taskInputProducts) - 1]['trunk_lot_id']) || count($this->taskInputProducts) == 0) {
            $this->taskInputProducts[] = ['trunk_lot_id' => '', 'consumed_quantity' => 1];
        }
    }

    // IS PRODUCT IN ORDER
    public function isProductInOrder($trunk_lot_id)
    {
        foreach ($this->taskInputProducts as $lot) {
            if ($lot['trunk_lot_id'] == $trunk_lot_id) {
                return true;
            }
        }
        return false;
    }

    // REMOVE PRODUCT
    public function removeProduct($index)
    {
        unset($this->taskInputProducts[$index]);
        $this->taskInputProducts = array_values($this->taskInputProducts);
    }

    public function save()
    {
        switch ($this->createForm['task_type_id']) {
            case 1:
                $task = Task::create([
                    'task_type_id' => $this->createForm['task_type_id'],
                    'task_status_id' => 2,
                    'started_at' => $this->createForm['started_at'],
                    'started_by' => 1,
                ]);

                foreach ($this->taskInputProducts as $product) {
                    $task->trunkLots()->attach($product['trunk_lot_id'], [
                        'consumed_quantity' => $product['consumed_quantity'],
                    ]);
                }

                $task->trunkLots->each(function ($trunkLot) {
                    $trunkLot->actual_quantity = $trunkLot->actual_quantity - $trunkLot->pivot->consumed_quantity;
                    $trunkLot->available = $trunkLot->actual_quantity ? true : false;
                    $trunkLot->save();
                });
                break;

            default:
                # code...
                break;
        }

        $this->reset('createForm', 'taskInputProducts');
        $this->resetTaskInputProducts();

        return redirect()->route('admin.tasks.index');

    }

    public function render()
    {
        return view('livewire.tasks.create-task');
    }
}
