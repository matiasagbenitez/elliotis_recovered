<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
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
}
