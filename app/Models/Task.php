<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Task extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    // Query Scope
    public function scopeFilter($query, $filters)
    {
        $query->when($filters['employee_id'] ?? null, function($query, $employee_id) {
            $query->where('started_by', $employee_id);
        })->when($filters['employee_id'] ?? null, function($query, $employee_id) {
            $query->where('finished_by', $employee_id);
        })->when($filters['task_status_id'] ?? null, function($query, $task_status_id) {
            $query->where('task_status_id', $task_status_id);
        })->when($filters['fromDate'] ?? null, function($query, $fromDate) {
            $query->where('started_at', '>=', $fromDate);
        })->when($filters['toDate'] ?? null, function($query, $toDate) {
            $query->where('finished_at', '<=', $toDate);
        });
    }

    public function typeOfTask()
    {
        return $this->belongsTo(TypeOfTask::class);
    }

    public function taskStatus()
    {
        return $this->belongsTo(TaskStatus::class);
    }

    public function lot()
    {
        return $this->hasOne(Lot::class);
    }

    // Many to Many with TrunkSublot
    public function trunkSublots()
    {
        return $this->belongsToMany(TrunkSublot::class, 'initial_task_detail', 'task_id', 'sublot_id')->withPivot('consumed_quantity', 'm2')->withTimestamps();
    }

    // Many to Many with Sublot
    public function inputSublotsDetails()
    {
        return $this->belongsToMany(Sublot::class, 'input_task_detail', 'task_id', 'sublot_id')->withPivot('consumed_quantity', 'm2')->withTimestamps();
    }

    public function outputSublotsDetails()
    {
        return $this->belongsToMany(Sublot::class, 'output_task_detail', 'task_id', 'sublot_id')->withPivot('produced_quantity', 'm2')->withTimestamps();
    }
}
