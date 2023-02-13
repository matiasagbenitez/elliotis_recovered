<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use PDF;

class TaskDetailPDFController extends Controller
{
    public function pdf(Task $task)
    {
        $initial = false;
        $transformation = false;
        $movement = false;
        $movement_transformation = false;

        $company_stats = $this->getCompanyStats();
        $report_title = 'Detalle de tarea N° ' . $task->id;
        $data = $this->taskData($task);

        $type_of_task = $task->typeOfTask;

        $results = [];

        if ($type_of_task->initial_task) {
            $results = $this->show_initial($task);
            $initial = true;
        } else if ($type_of_task->movement && !$type_of_task->transformation) {
            $results = $this->show_movement($task);
            $movement = true;
        } else if ($type_of_task->transformation && !$type_of_task->movement) {
            $results = $this->show_transformation($task);
            $transformation = true;
        } else if ($type_of_task->transformation && $type_of_task->movement) {
            $results = $this->show_movement_transformation($task);
            $movement_transformation = true;
        } else {
            abort(403);
        }

        // return view('livewire.tasks.task-detail-pdf', compact('company_stats', 'report_title', 'data', 'results'));
        $pdf = \PDF::loadView('livewire.tasks.task-detail-pdf', compact(
            'company_stats',
            'report_title',
            'data',
            'results',
            'initial',
            'transformation',
            'movement',
            'movement_transformation'
        ));
        return $pdf->stream('task_detail.pdf');
    }

    public function taskData($task)
    {
        $task_data = [
            'task_id' => $task->id,
            'type_of_task_name' => $task->typeOfTask->name,
            'started_by' => User::find($task->started_by)->name,
            'started_at' => Date::parse($task->started_at)->format('d-m-Y H:i'),
            'finished_by' => User::find($task->finished_by)->name,
            'finished_at' => Date::parse($task->finished_at)->format('d-m-Y H:i'),
            'origin_area' => $task->typeOfTask->originArea->name,
            'initial_phase' => $task->typeOfTask->initialPhase->name,
            'destination_area' => $task->typeOfTask->destinationArea->name,
            'final_phase' => $task->typeOfTask->finalPhase->name,
            'cancelled' => $task->cancelled,
            'cancelled_at' => $task->cancelled ? Date::parse($task->cancelled_at)->format('d-m-Y H:i') : null,
            'cancelled_by' => $task->cancelled ? User::find($task->cancelled_by)->name : null,
            'cancelled_reason' => $task->cancelled ? $task->cancelled_reason : null,
        ];

        return $task_data;
    }

    public function show_initial($task)
    {
        $input_data = [];
        foreach ($task->trunkSublots as $sublot) {
            $input_data[] = [
                'lot_code' => $sublot->trunkLot->code,
                'sublot_code' => $sublot->trunkLot->purchase->supplier->business_name,
                'product_name' => $sublot->product->name,
                'quantity' => $sublot->initial_quantity,
                'm2' => $sublot->m2 > 0 ? $sublot->m2 . ' m2' : 'N/A'
            ];
        }

        $output_data = [];
        foreach ($task->outputSublotsDetails as $sublot) {
            $output_data[] = [
                'lot_code' => $sublot->lot->code,
                'sublot_code' => $sublot->code,
                'product_name' => $sublot->product->name,
                'quantity' => $sublot->initial_quantity,
                'm2' => $sublot->m2 > 0 ? $sublot->m2 . ' m2' : 'N/A'
            ];
        }

        return [
            'input_data' => $input_data,
            'output_data' => $output_data,
        ];
    }

    public function show_transformation($task)
    {
        $input_data = [];
        foreach ($task->inputSublotsDetails as $sublot) {
            $input_data [] = [
                'lot_code' => $sublot->lot->code,
                'sublot_code' => $sublot->code,
                'product_name' => $sublot->product->name,
                'quantity' => $sublot->pivot->consumed_quantity,
                'm2' => $sublot->pivot->m2 > 0 ? $sublot->pivot->m2 . ' m2' : 'N/A',
            ];
        }

        $output_data = [];
        foreach ($task->outputSublotsDetails as $sublot) {
            $output_data [] = [
                'lot_code' => $sublot->lot->code,
                'sublot_code' => $sublot->code,
                'product_name' => $sublot->product->name,
                'quantity' => $sublot->initial_quantity,
                'm2' => $sublot->pivot->m2 > 0 ? $sublot->pivot->m2 . ' m2' : 'N/A',
            ];
        }

        return [
            'input_data' => $input_data,
            'output_data' => $output_data,
        ];
    }

    public function show_movement($task)
    {
        $input_data = [];
        foreach ($task->inputSublotsDetails as $sublot) {
            $input_data [] = [
                'lot_code' => $sublot->lot->code,
                'sublot_code' => $sublot->code,
                'product_name' => $sublot->product->name,
                'quantity' => $sublot->initial_quantity,
                'm2' => $sublot->initial_m2 > 0 ? $sublot->initial_m2 . ' m2' : 'N/A',
            ];
        }

        $output_data = [];
        foreach ($task->outputSublotsDetails as $sublot) {
            $output_data [] = [
                'lot_code' => $sublot->lot->code,
                'sublot_code' => $sublot->code,
                'product_name' => $sublot->product->name,
                'quantity' => $sublot->pivot->produced_quantity,
                'm2' => $sublot->pivot->m2 > 0 ? $sublot->pivot->m2 . ' m2' : 'N/A',
            ];
        }

        return [
            'input_data' => $input_data,
            'output_data' => $output_data,
        ];
    }

    public function show_movement_transformation($task)
    {
        $input_data = [];
        foreach ($task->inputSublotsDetails as $sublot) {
            $input_data [] = [
                'lot_code' => $sublot->lot->code,
                'sublot_code' => $sublot->code,
                'product_name' => $sublot->product->name,
                'quantity' => $sublot->initial_quantity,
                'm2' => $sublot->initial_m2 > 0 ? $sublot->initial_m2 . ' m2' : 'N/A',
            ];
        }

        $output_data = [];
        foreach ($task->outputSublotsDetails as $sublot) {
            $output_data [] = [
                'lot_code' => $sublot->lot->code,
                'sublot_code' => $sublot->code,
                'product_name' => $sublot->product->name,
                'quantity' => $sublot->pivot->produced_quantity,
                'm2' => $sublot->pivot->m2 > 0 ? $sublot->pivot->m2 . ' m2' : 'N/A',
            ];
        }

        return [
            'input_data' => $input_data,
            'output_data' => $output_data,
        ];
    }

    public function getCompanyStats()
    {
        $company = Company::find(1);
        $company_stats = [
            'name' => $company->name,
            'cuit' => $company->cuit,
            'slogan' => $company->slogan,
            'address' => $company->address,
            'phone' => $company->phone,
            'email' => $company->email,
            'cp' => $company->cp,
            'logo' => $company->logo,
            'date' => date('d/m/Y H:i'),
            'user' => User::find(auth()->user()->id)->name
        ];

        return $company_stats;
    }
}
