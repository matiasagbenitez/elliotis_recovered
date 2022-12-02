<?php

namespace App\Http\Livewire\Tasks;

use App\Models\Task;
use App\Models\TaskStatus;
use App\Models\User;
use Livewire\Component;
use App\Models\TaskType;
use App\Models\TrunkLot;
use Illuminate\Support\Str;

class CreateTask extends Component
{
    public $task_id, $task_type_id, $task_status_id, $task_type_name, $user_who_started, $started_at;

    public $taskInputProducts = [];
    public $allInputProducts = [];

    protected $listeners = ['testAlert' => 'testAlert'];

    public $createForm = [
        'task_type_id' => 1,
        'task_status_id' => 2,
        'started_at' => '',
        'started_by' => 1,
        'finished_at' => '',
        'finished_by' => 1,
    ];

    public function mount(Task $task)
    {
        if ($task->task_status_id != 2) {
            abort(404);
        }

        $this->task_id = $task->id;
        $this->task_type_id = $task->task_type_id;
        $this->task_status_id = $task->task_status_id;
        $this->task_type_name = $task->taskType->name;
        $this->user_who_started = User::find($task->started_by)->name;
        $this->started_at = $task->started_at;
        $this->filterInputProducts($task->taskType->id);
    }

    public function filterInputProducts($id)
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
        // Check status
        $task = Task::find($this->task_id);
        $status_id = TaskStatus::find($task->task_status_id)->id;

        switch ($this->createForm['task_type_id']) {

            // CORTE DE ROLLOS
            case 1:
                if ($status_id == 2) {

                    // Verify input products quantities
                    foreach ($this->taskInputProducts as $product) {
                        $trunk_lot = TrunkLot::find($product['trunk_lot_id']);
                        if ($product['consumed_quantity'] > $trunk_lot->actual_quantity) {
                            $this->emit('error', 'Lote #' . $trunk_lot->code . ' ¡Cantidad ingresada no disponible!');
                            return;
                        }
                    }

                    // Update task
                    $task = Task::find($this->task_id);
                    $task->update([
                        'task_status_id' => 3,
                        'finished_at' => now(),
                        'finished_by' => auth()->user()->id,
                    ]);

                    // Input products detail
                    foreach ($this->taskInputProducts as $product) {
                        $task->trunkLots()->attach($product['trunk_lot_id'], [
                            'consumed_quantity' => $product['consumed_quantity'],
                        ]);
                    }

                    // Update trunk lots
                    $task->trunkLots->each(function ($trunkLot) {
                        $trunkLot->actual_quantity = $trunkLot->actual_quantity - $trunkLot->pivot->consumed_quantity;
                        $trunkLot->available = $trunkLot->actual_quantity ? true : false;
                        $trunkLot->save();
                    });

                    // Create a lot
                    $task->lot()->create([
                        'code' => Str::random(6),
                    ]);

                    session()->flash('flash.banner', '¡Tarea finalizada correctamente!');
                    return redirect()->route('admin.tasks.manage', ['taskType' => $this->task_type_id]);

                } else {
                    session()->flash('flash.banner', '¡No puedes finalizar esta tare porque la misma ya se encuentra finalizada!');
                    session()->flash('flash.bannerStyle', 'danger');
                    return redirect()->route('admin.tasks.manage', ['taskType' => $this->task_type_id]);
                }

                break;

            default:
                # code...
                break;
        }

        $this->reset('createForm', 'taskInputProducts');
        $this->resetTaskInputProducts();

        return redirect()->route('admin.tasks.index');
    }

    public function testAlert()
    {
        dd('test');
        // $this->emitTo('tasks.tasks-management', 'alert', ['type' => 'success', 'message' => '¡Tarea finalizada correctamente!']);
    }

    public function render()
    {
        return view('livewire.tasks.create-task');
    }
}
