<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Sublot extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function lot()
    {
        return $this->belongsTo(Lot::class);
    }

    public function phase()
    {
        return $this->belongsTo(Phase::class);
    }

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Many to Many with Task
    public function inputTasksDetails()
    {
        return $this->belongsToMany(Task::class, 'input_task_detail', 'sublot_id', 'task_id')->withPivot('consumed_quantity')->withTimestamps();
    }

    public function outputTasksDetails()
    {
        return $this->belongsToMany(Task::class, 'output_task_detail', 'sublot_id', 'task_id')->withPivot('produced_quantity')->withTimestamps();
    }
}
