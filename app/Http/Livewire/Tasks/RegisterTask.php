<?php

namespace App\Http\Livewire\Tasks;

use App\Models\Task;
use App\Models\User;
use App\Models\Sublot;
use Livewire\Component;
use App\Models\TypeOfTask;
use App\Models\TrunkSublot;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Date;

class RegisterTask extends Component
{
    public $task, $type_of_task;
    public $sublots;
    public $info = [];
    public $movement, $transformation, $both, $initial;
    protected $listeners = ['presave'];

    public $inputSelects = [];
    public $inputSublots = [];

    public $outputSelects = [];
    public $outputProducts = [];
    public $followingProducts = [];

    public $createForm = [
        'type_of_task' => '',
        'task_status_id' => '',
        'started_at' => '',
        'started_by' => '',
        'finished_at' => '',
        'finished_by' => '',
    ];

    public function mount(TypeOfTask $task_type, Task $task)
    {
        if (!$task || $task->started_by != auth()->user()->id || !$task->taskStatus->running) {
            abort(403);
        }

        $this->task = $task;
        $this->type_of_task = $task_type;
        $this->info = [
            'task_name' => $this->type_of_task->name,
            'user_who_started' => User::find($this->task->started_by)->name,
            'started_at' => Date::parse($this->task->started_at)->format('d-m-Y H:i'),
            'initial_phase' => $this->type_of_task->initialPhase->name,
            'origin_area' => $this->type_of_task->originArea->name,
            'final_phase' => $this->type_of_task->finalPhase->name,
            'destination_area' => $this->type_of_task->destinationArea->name,
        ];

        if ($this->type_of_task->initial_task) {
            $this->initial = true;
        } else if ($this->type_of_task->movement && !$this->type_of_task->transformation) {
            $this->movement = true;
        } else if ($this->type_of_task->transformation && !$this->type_of_task->movement) {
            $this->transformation = true;
        } else if ($this->type_of_task->transformation && $this->type_of_task->movement) {
            $this->both = true;
        } else {
            abort(403);
        }

        $this->getSublots();
    }

    // OBTENER SUBLOTES DE ENTRADA
    public function getSublots()
    {
        if ($this->type_of_task->initial_task) {
            $sublots = TrunkSublot::where('area_id', $this->type_of_task->origin_area_id)->where('available', true)->get();
        } else {
            $sublots = Sublot::where('phase_id', $this->type_of_task->initial_phase_id)->where('area_id', $this->type_of_task->origin_area_id)->where('available', true)->get();
        }

        // Preparamos la información para el select
        foreach ($sublots as $sublot) {

            if ($sublot->trunkLot) {
                $lot_code = 'Lote: ' . $sublot->trunkLot->code . ' - ' . $sublot->trunkLot->purchase->supplier->business_name;
            } else {
                $lot_code = 'Lote: ' . $sublot->lot->code;
            }

            $this->inputSublots[] = [
                'id' => $sublot->id,
                'product_name' => $sublot->product->name,
                'lot_code' => $lot_code,
                'sublot_code' => $sublot->code ? 'Sublote: ' . $sublot->code : '',
                'actual_quantity' => $sublot->actual_quantity,
            ];
        }

        $this->inputSelects = [
            ['sublot_id' => '', 'consumed_quantity' => 1]
        ];
    }

    // SI LA TAREA ES DE TRANSFORMACIÓN, OBTENER PRODUCTOS DE SALIDA
    public function updatedInputSelects()
    {
        if ($this->transformation) {
            $this->reset('outputProducts');
            $this->getFollowingProducts();
        }
    }

    // OBTENER PRODUCTOS DE SALIDA
    public function getFollowingProducts()
    {
        $followingProducts = [];

        foreach ($this->inputSelects as $input) {
            if ($input['sublot_id'] != '') {
                $sublot = Sublot::find($input['sublot_id']);
                $followingProducts[] = $sublot->product->followingProducts;
            }
        }

        // Eliminamos los repetidos
        $followingProducts = collect($followingProducts)->collapse()->unique('id')->values()->all();

        foreach ($followingProducts as $product) {
            $this->outputProducts[] = [
                'id' => $product->id,
                'name' => $product->name,
            ];
        }

        $this->outputSelects = [
            ['product_id' => '', 'produced_quantity' => 1]
        ];
    }

    // RESETEAR SELECT DE SUBLOTES
    public function resetInputSelects()
    {
        $this->reset('inputSelects');
    }

    // AGREGAR SUBLOTE
    public function addInputSelect()
    {
        if (count($this->inputSelects) == count($this->inputSublots)) {
            return;
        }

        if (!empty($this->inputSelects[count($this->inputSelects) - 1]['sublot_id']) || count($this->inputSelects) == 0) {
            $this->inputSelects[] = ['sublot_id' => '', 'consumed_quantity' => 1];
        }
    }

    // AGREGAR PRODUCTO DE SALIDA
    public function addOutputSelect()
    {
        if (count($this->outputSelects) == count($this->outputProducts)) {
            return;
        }

        if (!empty($this->outputSelects[count($this->outputSelects) - 1]['product_id']) || count($this->outputSelects) == 0) {
            $this->outputSelects[] = ['product_id' => '', 'produced_quantity' => 1];
        }
    }

    // CONTROLAR REPETICIÓN DE SUBLOTES
    public function isSublotInInputSelect($id)
    {
        foreach ($this->inputSelects as $sublot) {
            if ($sublot['sublot_id'] == $id) {
                return true;
            }
        }
        return false;
    }

    // CONTROLAR REPETICIÓN DE PRODUCTOS DE SALIDA
    public function isProductInOutputSelect($id)
    {
        foreach ($this->outputSelects as $product) {
            if ($product['product_id'] == $id) {
                return true;
            }
        }
        return false;
    }

    // REMOVE INPUT PRODUCT
    public function removeInputSelect($index)
    {
        unset($this->inputSelects[$index]);
        $this->inputSelects = array_values($this->inputSelects);

        if ($this->transformation) {
            $this->reset('outputProducts');
            $this->getFollowingProducts();
        }

    }

    // REMOVE OUTPUT PRODUCT
    public function removeOutputSelect($index)
    {
        unset($this->outputSelects[$index]);
        $this->outputSelects = array_values($this->outputSelects);
    }

    // TIPO DE TAREA
    public function presave()
    {
        // TAREA INICIAL
        if ($this->type_of_task->initial_task) {

            $this->save_initial();

            // TAREA DE MOVIMIENTO
        } else if ($this->type_of_task->movement && !$this->type_of_task->transformation) {

            $this->save_movement();

            // TAREA DE TRANSFORMACIÓN
        } else if ($this->type_of_task->transformation && !$this->type_of_task->movement) {

            $this->save_transformation();

            // TAREA DE MOVIMIENTO Y TRANSFORMACIÓN
        } else if ($this->type_of_task->transformation && $this->type_of_task->movement) {
            dd('both');

            // ERROR
        } else {
            abort(403);
        }
    }

    public function save_initial()
    {
        try {
            // Validar cantidades consumidas
            foreach ($this->inputSelects as $sublot) {
                $trunk_sublot = TrunkSublot::find($sublot['sublot_id']);
                if ($sublot['consumed_quantity'] > $trunk_sublot->actual_quantity) {
                    $this->emit('error', 'La cantidad consumida no puede ser mayor a la cantidad actual del sublote.');
                    return;
                }
            }

            // Validación
            $this->validate([
                'inputSelects.*.sublot_id' => 'required',
                'inputSelects.*.consumed_quantity' => 'required|numeric|min:1'
            ]);

            // Completamos la tabla intermedia (initial_task_detail)
            $this->task->trunkSublots()->sync($this->inputSelects);

            // Creamos el lote
            $this->task->lot()->create([
                'code' => 'L' . $this->task->typeOfTask->finalPhase->prefix . '-' . $this->task->id,
            ]);

            // Creamos los sublotes de salida
            foreach ($this->inputSelects as $sublot) {

                // Actualizamos el sublote de rollos
                $trunk_sublot = TrunkSublot::find($sublot['sublot_id']);
                $trunk_sublot->update([
                    'actual_quantity' => $trunk_sublot->actual_quantity - $sublot['consumed_quantity'],
                    'available' => $trunk_sublot->actual_quantity - $sublot['consumed_quantity'] > 0 ? 1 : 0
                ]);

                // Creamos el sublote de salida
                $this->task->lot->sublots()->create([
                    'code' => 'S' . $this->task->typeOfTask->finalPhase->prefix . '-' . $this->task->id,
                    'phase_id' => $this->task->typeOfTask->final_phase_id,
                    'area_id' => $this->task->typeOfTask->destination_area_id,
                    'product_id' => $trunk_sublot->product_id,
                    'initial_quantity' => $sublot['consumed_quantity'],
                    'actual_quantity' => $sublot['consumed_quantity'],
                ]);
            }

            // Actualizamos la tarea
            $this->task->update([
                'task_status_id' => 2,
                'finished_at' => now(),
                'finished_by' => auth()->user()->id,
            ]);

            // Redireccionamos con flash message
            $name = Str::upper($this->task->typeOfTask->name);
            session()->flash('flash.banner', 'Tarea de tipo ' . $name . ' registrada correctamente.');
            return redirect()->route('admin.tasks.index');

        } catch (\Throwable $th) {
            dd($th);
        }
    }

    public function save_transformation()
    {
        try {
            // Validar las cantidades consumidas
            foreach ($this->inputSelects as $item) {
                $sublot = Sublot::find($item['sublot_id']);
                if ($item['consumed_quantity'] > $sublot->actual_quantity) {
                    $this->emit('error', 'La cantidad consumida no puede ser mayor a la cantidad actual del sublote.');
                    return;
                }
            }

            // Validación
            $this->validate([
                'inputSelects.*.sublot_id' => 'required',
                'inputSelects.*.consumed_quantity' => 'required|numeric|min:1',
                'outputSelects.*.product_id' => 'required',
                'outputSelects.*.produced_quantity' => 'required|numeric|min:1',
            ]);

            // Completamos la tabla intermedia (input_task_detail)
            $this->task->inputSublotsDetails()->sync($this->inputSelects);

            // Actualizamos los sublotes de entrada
            foreach ($this->inputSelects as $item) {
                $sublot = Sublot::find($item['sublot_id']);
                $sublot->update([
                    'actual_quantity' => $sublot->actual_quantity - $item['consumed_quantity'],
                    'available' => $sublot->actual_quantity - $item['consumed_quantity'] > 0 ? 1 : 0
                ]);
            }

            // Creamos el lote
            $this->task->lot()->create([
                'code' => 'L' . $this->task->typeOfTask->finalPhase->prefix . '-' . $this->task->id,
            ]);

            // Creamos los sublotes de salida
            foreach ($this->outputSelects as $item) {
                $this->task->lot->sublots()->create([
                    'code' => 'S' . $this->task->typeOfTask->finalPhase->prefix . '-' . $this->task->id,
                    'phase_id' => $this->task->typeOfTask->final_phase_id,
                    'area_id' => $this->task->typeOfTask->destination_area_id,
                    'product_id' => $item['product_id'],
                    'initial_quantity' => $item['produced_quantity'],
                    'actual_quantity' => $item['produced_quantity'],
                ]);

                // Sublot id
                $sublot = Sublot::where('code', 'S' . $this->task->typeOfTask->finalPhase->prefix . '-' . $this->task->id)->first();

                // Creamos un arreglo para completar la tabla intermedia (output_task_detail)
                $aux[] = [
                    'sublot_id' => $sublot->id,
                    'produced_quantity' => $item['produced_quantity'],
                ];

                $this->task->outputSublotsDetails()->sync($aux);
            }

            // Actualizamos la tarea
            $this->task->update([
                'task_status_id' => 2,
                'finished_at' => now(),
                'finished_by' => auth()->user()->id,
            ]);

            // Redireccionamos con flash message
            $name = Str::upper($this->task->typeOfTask->name);
            session()->flash('flash.banner', 'Tarea de tipo ' . $name . ' registrada correctamente.');
            return redirect()->route('admin.tasks.index');

        } catch (\Throwable $th) {
            dd($th);
        }
    }

    public function save_movement()
    {
        try {
            // Validar las cantidades consumidas
            foreach ($this->inputSelects as $item) {
                $sublot = Sublot::find($item['sublot_id']);
                if ($item['consumed_quantity'] > $sublot->actual_quantity) {
                    $this->emit('error', 'La cantidad consumida no puede ser mayor a la cantidad actual del sublote.');
                    return;
                }
            }

            // Validación
            $this->validate([
                'inputSelects.*.sublot_id' => 'required',
                'inputSelects.*.consumed_quantity' => 'required|numeric|min:1'
            ]);

            // Completamos la tabla intermedia (input_task_detail)
            $this->task->inputSublotsDetails()->sync($this->inputSelects);

            // Creamos el lote
            $this->task->lot()->create([
                'code' => 'L' . $this->task->typeOfTask->finalPhase->prefix . '-' . $this->task->id,
            ]);

            // Actualizamos los sublotes de entrada
            foreach ($this->inputSelects as $item) {
                $sublot = Sublot::find($item['sublot_id']);
                $sublot->update([
                    'actual_quantity' => $sublot->actual_quantity - $item['consumed_quantity'],
                    'available' => $sublot->actual_quantity - $item['consumed_quantity'] > 0 ? 1 : 0
                ]);

                // Creamos el sublote de salida
                $this->task->lot->sublots()->create([
                    'code' => 'S' . $this->task->typeOfTask->finalPhase->prefix . '-' . $this->task->id,
                    'phase_id' => $this->task->typeOfTask->final_phase_id,
                    'area_id' => $this->task->typeOfTask->destination_area_id,
                    'product_id' => $sublot->product_id,
                    'initial_quantity' => $item['consumed_quantity'],
                    'actual_quantity' => $item['consumed_quantity'],
                ]);
            }

            // Actualizamos la tarea
            $this->task->update([
                'task_status_id' => 2,
                'finished_at' => now(),
                'finished_by' => auth()->user()->id,
            ]);

            // Redireccionamos con flash message
            $name = Str::upper($this->task->typeOfTask->name);
            session()->flash('flash.banner', 'Tarea de tipo ' . $name . ' registrada correctamente.');
            return redirect()->route('admin.tasks.index');

        } catch (\Throwable $th) {
            dd($th);
        }
    }

    public function render()
    {
        return view('livewire.tasks.register-task');
    }
}
