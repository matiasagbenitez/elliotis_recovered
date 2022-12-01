<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskType extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    // Relationship with Task
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    // Relationship with Area
    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    // Relationship with Phase
    public function initial_phase()
    {
        return $this->belongsTo(Phase::class, 'initial_phase_id');
    }

    // Relationship with Phase
    public function final_phase()
    {
        return $this->belongsTo(Phase::class, 'final_phase_id');
    }
}
