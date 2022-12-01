<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    // Relationship with TaskType
    public function taskType()
    {
        return $this->belongsTo(TaskType::class);
    }

    // Relationship with TaskStatus
    public function taskStatus()
    {
        return $this->belongsTo(TaskStatus::class);
    }

    // Relationship with trunkLots
    public function trunkLots()
    {
        return $this->belongsToMany(TrunkLot::class)->withPivot('id', 'task_id', 'trunk_lot_id', 'consumed_quantity')->withTimestamps();
    }

    // Has one Lot
    public function lot()
    {
        return $this->hasOne(Lot::class);
    }
}
