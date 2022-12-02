<?php

namespace App\Http\Livewire\Tasks;

use App\Models\Product;
use App\Models\Sublot;
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
    public $initial_phase_name, $final_phase_name;

    public $sublots;

    public $taskInputProducts = [];
    public $allInputProducts = [];

    public $taskOutputProducts = [];
    public $allOutputProducts = [];

    protected $listeners = ['save'];

    public $createForm = [
        'task_type_id' => '',
        'task_status_id' => '',
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
        $this->initial_phase_name = $task->taskType->initial_phase->name;
        $this->final_phase_name = $task->taskType->final_phase->name;

        $this->sublots = Sublot::all();

        $this->filterInputProducts($task->taskType->id);
        $this->filterOutputProducts($task->taskType->id);
    }

    public function filterInputProducts($task_type_id)
    {
        switch ($task_type_id) {
            case 1:
                $this->allInputProducts = TrunkLot::where('available', true)->orderBy('id', 'asc')->get();
                $this->taskInputProducts = [
                    ['trunk_lot_id' => '', 'consumed_quantity' => 1]
                ];
                break;

            case 2:
                foreach ($this->sublots as $sublot) {
                    if ($sublot->product->phase_id == 2) {
                        $this->allInputProducts[] = [
                            'id' => $sublot->id,
                            'actual_quantity' => $sublot->actual_quantity,
                            'product_name' => $sublot->product->name,
                            'sublot_code' => $sublot->code,
                            'lot_code' => $sublot->lot->code,
                        ];
                    }
                }

                $this->taskInputProducts = [
                    ['sublot_id' => '', 'consumed_quantity' => 1]
                ];
                break;

            case 3:
                foreach ($this->sublots as $sublot) {
                    if ($sublot->product->phase_id == 3) {
                        $this->allInputProducts[] = [
                            'id' => $sublot->id,
                            'actual_quantity' => $sublot->actual_quantity,
                            'product_name' => $sublot->product->name,
                            'sublot_code' => $sublot->code,
                            'lot_code' => $sublot->lot->code,
                        ];
                    }
                }

                $this->taskInputProducts = [
                    ['sublot_id' => '', 'consumed_quantity' => 1]
                ];
                break;

            default:
                $this->reset('allInputProducts', 'taskInputProducts');
                break;
        }
    }

    public function filterOutputProducts($id)
    {
        switch ($id) {
            case 1:
                $this->allOutputProducts = Product::where('phase_id', 2)->orderBy('id', 'asc')->get();
                $this->taskOutputProducts = [
                    ['product_id' => '', 'produced_quantity' => 1]
                ];

                break;

            case 2:
                $this->allOutputProducts = Product::where('phase_id', 3)->orderBy('id', 'asc')->get();
                $this->taskOutputProducts = [
                    ['product_id' => '', 'produced_quantity' => 1]
                ];

                break;

            case 3:
                $this->allOutputProducts = Product::where('phase_id', 4)->orderBy('id', 'asc')->get();
                $this->taskOutputProducts = [
                    ['product_id' => '', 'produced_quantity' => 1]
                ];

                break;
            default:
        }
    }

    // RESET TASK INPUT PRODUCTS
    public function resetTaskInputProducts()
    {
        $this->reset('taskInputProducts');
    }

    // RESET TASK OUTPUT PRODUCTS
    public function resetTaskOutputProducts()
    {
        $this->reset('taskOutputProducts');
    }

    // ADD PRODUCT
    public function addInputProduct()
    {
        switch ($this->task_type_id) {
            case 1:
                if (count($this->taskInputProducts) == count($this->allInputProducts)) {
                    return;
                }

                if (!empty($this->taskInputProducts[count($this->taskInputProducts) - 1]['trunk_lot_id']) || count($this->taskInputProducts) == 0) {
                    $this->taskInputProducts[] = ['trunk_lot_id' => '', 'consumed_quantity' => 1];
                }
                break;

            default:
                if (count($this->taskInputProducts) == count($this->allInputProducts)) {
                    return;
                }

                if (!empty($this->taskInputProducts[count($this->taskInputProducts) - 1]['sublot_id']) || count($this->taskInputProducts) == 0) {
                    $this->taskInputProducts[] = ['sublot_id' => '', 'consumed_quantity' => 1];
                }
                break;
        }
    }

    // ADD OUTPUT PRODUCT
    public function addOutputProduct()
    {
        switch ($this->task_type_id) {
            case 1:
                if (count($this->taskOutputProducts) == count($this->allOutputProducts)) {
                    return;
                }

                if (!empty($this->taskOutputProducts[count($this->taskOutputProducts) - 1]['product_id']) || count($this->taskOutputProducts) == 0) {
                    $this->taskOutputProducts[] = ['product_id' => '', 'produced_quantity' => 1];
                }
                break;

            default:
                if (count($this->taskOutputProducts) == count($this->allOutputProducts)) {
                    return;
                }

                if (!empty($this->taskOutputProducts[count($this->taskOutputProducts) - 1]['product_id']) || count($this->taskOutputProducts) == 0) {
                    $this->taskOutputProducts[] = ['product_id' => '', 'produced_quantity' => 1];
                }
                break;
        }
    }

    // IS PRODUCT IN INPUT ORDER
    public function isProductInInputOrder($id)
    {
        switch ($this->task_type_id) {
            case 1:
                foreach ($this->taskInputProducts as $lot) {
                    if ($lot['trunk_lot_id'] == $id) {
                        return true;
                    }
                }
                return false;
                break;

            default:
                foreach ($this->taskInputProducts as $sublot) {
                    if ($sublot['sublot_id'] == $id) {
                        return true;
                    }
                }
                return false;
                break;
        }
    }

    // IS PRODUCT IN OUTPUT ORDER
    public function isProductInOutputOrder($id)
    {
        foreach ($this->taskOutputProducts as $product) {
            if ($product['product_id'] == $id) {
                return true;
            }
        }
        return false;
    }

    // REMOVE INPUT PRODUCT
    public function removeInputProduct($index)
    {
        unset($this->taskInputProducts[$index]);
        $this->taskInputProducts = array_values($this->taskInputProducts);
    }

    // REMOVE OUTPUT PRODUCT
    public function removeOutputProduct($index)
    {
        unset($this->taskOutputProducts[$index]);
        $this->taskOutputProducts = array_values($this->taskOutputProducts);
    }

    public function save()
    {
        // Check for not updating on other tabs
        $task = Task::find($this->task_id);
        $status_id = TaskStatus::find($task->task_status_id)->id;

        switch ($this->task_type_id) {

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
                        'code' => 'LFH-' . rand(1000, 9999),
                    ]);

                    // Output products detail
                    foreach ($this->taskOutputProducts as $product) {
                        $task->outputProducts()->attach($product['product_id'], [
                            'lot_id' => $task->lot->id,
                            'produced_quantity' => $product['produced_quantity'],
                        ]);
                        $task->lot->sublots()->create([
                            'code' => 'SFH-' . rand(1000, 9999),
                            'product_id' => $product['product_id'],
                            'initial_quantity' => $product['produced_quantity'],
                            'actual_quantity' => $product['produced_quantity'],
                        ]);
                    }

                    session()->flash('flash.banner', '¡Tarea finalizada correctamente!');
                    return redirect()->route('admin.tasks.manage', ['taskType' => $this->task_type_id]);
                } else {
                    session()->flash('flash.banner', '¡No puedes finalizar esta tare porque la misma ya se encuentra finalizada!');
                    session()->flash('flash.bannerStyle', 'danger');
                    return redirect()->route('admin.tasks.manage', ['taskType' => $this->task_type_id]);
                }

                break;

            // MACHIMBRADO
            case 2:
                if ($status_id == 2) {

                    // Verify input products quantities
                    foreach ($this->taskInputProducts as $product) {
                        $sublot = Sublot::find($product['sublot_id']);
                        if ($product['consumed_quantity'] > $sublot->actual_quantity) {
                            $this->emit('error', 'Lote #' . $sublot->code . ' ¡Cantidad ingresada no disponible!');
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

                    // Create a lot
                    $task->lot()->create([
                        'code' => 'LFM-' . rand(1000, 9999),
                    ]);

                    // dd($this->taskInputProducts);
                    // Input products detail
                    foreach ($this->taskInputProducts as $product) {
                        $product_id = Sublot::find($product['sublot_id'])->product_id;
                        $task->inputProducts()->attach($product_id, [
                            'sublot_id' => $product['sublot_id'],
                            'consumed_quantity' => $product['consumed_quantity'],
                        ]);
                    }

                    // Update sublots
                    $task->outputProducts->each(function ($sublot) {
                        $sublot->actual_quantity = $sublot->actual_quantity - $sublot->pivot->consumed_quantity;
                        $sublot->save();
                    });

                    // Output products detail
                    foreach ($this->taskOutputProducts as $product) {
                        $task->outputProducts()->attach($product['product_id'], [
                            'lot_id' => $task->lot->id,
                            'produced_quantity' => $product['produced_quantity'],
                        ]);
                        $task->lot->sublots()->create([
                            'code' => 'SFM-' . rand(1000, 9999),
                            'product_id' => $product['product_id'],
                            'initial_quantity' => $product['produced_quantity'],
                            'actual_quantity' => $product['produced_quantity'],
                        ]);
                    }
                    // dd('ok');
                    session()->flash('flash.banner', '¡Tarea finalizada correctamente!');
                    return redirect()->route('admin.tasks.manage', ['taskType' => $this->task_type_id]);
                } else {
                    dd('not ok');
                    session()->flash('flash.banner', '¡No puedes finalizar esta tare porque la misma ya se encuentra finalizada!');
                    session()->flash('flash.bannerStyle', 'danger');
                    return redirect()->route('admin.tasks.manage', ['taskType' => $this->task_type_id]);
                }

                break;

            // EMBALAJE
            case 3:
                if ($status_id == 2) {

                    // Verify input products quantities
                    foreach ($this->taskInputProducts as $product) {
                        $sublot = Sublot::find($product['sublot_id']);
                        if ($product['consumed_quantity'] > $sublot->actual_quantity) {
                            $this->emit('error', 'Lote #' . $sublot->code . ' ¡Cantidad ingresada no disponible!');
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

                    // Create a lot
                    $task->lot()->create([
                        'code' => 'LPGM-' . rand(1000, 9999),
                    ]);

                    // dd($this->taskInputProducts);
                    // Input products detail
                    foreach ($this->taskInputProducts as $product) {
                        $product_id = Sublot::find($product['sublot_id'])->product_id;
                        $task->inputProducts()->attach($product_id, [
                            'sublot_id' => $product['sublot_id'],
                            'consumed_quantity' => $product['consumed_quantity'],
                        ]);
                    }

                    // Update sublots
                    $task->outputProducts->each(function ($sublot) {
                        $sublot->actual_quantity = $sublot->actual_quantity - $sublot->pivot->consumed_quantity;
                        $sublot->save();
                    });

                    // Output products detail
                    foreach ($this->taskOutputProducts as $product) {
                        $task->outputProducts()->attach($product['product_id'], [
                            'lot_id' => $task->lot->id,
                            'produced_quantity' => $product['produced_quantity'],
                        ]);
                        $task->lot->sublots()->create([
                            'code' => 'SPGM-' . rand(1000, 9999),
                            'product_id' => $product['product_id'],
                            'initial_quantity' => $product['produced_quantity'],
                            'actual_quantity' => $product['produced_quantity'],
                        ]);
                    }
                    // dd('ok');
                    session()->flash('flash.banner', '¡Tarea finalizada correctamente!');
                    return redirect()->route('admin.tasks.manage', ['taskType' => $this->task_type_id]);
                } else {
                    dd('not ok');
                    session()->flash('flash.banner', '¡No puedes finalizar esta tare porque la misma ya se encuentra finalizada!');
                    session()->flash('flash.bannerStyle', 'danger');
                    return redirect()->route('admin.tasks.manage', ['taskType' => $this->task_type_id]);
                }

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
