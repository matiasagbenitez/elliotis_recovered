<?php

namespace App\Http\Livewire\Tasks;

use App\Models\Sublot;
use App\Models\Task;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Date;

class ShowFinishedTask extends Component
{
    public $task;
    public $taskData = [];
    public $inputProductsData = [];
    public $outputProductsData = [];

    public function mount(Task $task)
    {
        if ($task->task_status_id != 3) {
            abort(404);
        }

        $this->task = $task;
        $this->taskData = $this->getTaskData($task);
        $this->inputProductsData = $this->getInputProductsData($task);
        $this->outputProductsData = $this->getOutputProductsData($task);
    }

    public function getTaskData($task)
    {
        $taskData = [];

        $taskData['id'] = $task->id;
        $taskData['area'] = $task->taskType->area->name;
        $taskData['task_type_name'] = $task->taskType->name;
        $taskData['task_status_name'] = $task->taskStatus->name;
        $taskData['started_at'] = Date::parse($task->started_at)->format('d/m/Y H:i');
        $taskData['finished_at'] = Date::parse($task->finished_at)->format('d/m/Y H:i');
        $taskData['started_by'] = User::find($task->started_by)->name;
        $taskData['finished_by'] = User::find($task->finished_by)->name;

        return $taskData;
    }

    public function getInputProductsData($task)
    {
        $inputProductsData = [];

        switch ($task->task_type_id) {
            case 1:
                foreach ($task->trunkLots as $trunkLot) {
                    $inputProductsData[] = [
                        'lot_code' => $trunkLot->trunk_purchase->code,
                        'sublot_code' => $trunkLot->code,
                        'product_name' => $trunkLot->product->name,
                        'consumed_quantity' => $trunkLot->pivot->consumed_quantity,
                    ];
                }
                break;

            default:
                foreach ($task->inputProducts as $inputProduct) {
                    $sublot = Sublot::find($inputProduct->pivot->sublot_id);
                    $lot = $sublot->lot;
                    $inputProductsData[] = [
                        'lot_code' => $lot->code,
                        'sublot_code' => $sublot->code,
                        'product_name' => $inputProduct->name,
                        'consumed_quantity' => $inputProduct->pivot->consumed_quantity,
                    ];
                }
                break;
        }
        return $inputProductsData;
    }

    public function getOutputProductsData($task)
    {
        $outputProductsData = [];

        switch ($task->task_type_id) {
            case 1:
                foreach ($task->outputProducts as $outputProduct) {
                    $outputProductsData[] = [
                        'lot_code' => $task->lot->code,
                        'sublot_code' => Sublot::where('lot_id', $task->lot->id)->where('product_id', $outputProduct->id)->first()->code,
                        'product_name' => $outputProduct->name,
                        'produced_quantity' => $outputProduct->pivot->produced_quantity,
                    ];
                }
                break;

            default:
                foreach ($task->outputProducts as $outputProduct) {
                    $outputProductsData[] = [
                        'lot_code' => $task->lot->code,
                        'sublot_code' => Sublot::where('lot_id', $task->lot->id)->where('product_id', $outputProduct->id)->first()->code,
                        'product_name' => $outputProduct->name,
                        'produced_quantity' => $outputProduct->pivot->produced_quantity,
                    ];
                }
                break;
        }
        return $outputProductsData;
    }

    public function render()
    {
        return view('livewire.tasks.show-finished-task');
    }
}
