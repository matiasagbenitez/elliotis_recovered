<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeOfTask extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function originArea()
    {
        return $this->belongsTo(Area::class, 'origin_area_id');
    }

    public function destinationArea()
    {
        return $this->belongsTo(Area::class, 'destination_area_id');
    }

    public function initialPhase()
    {
        return $this->belongsTo(Phase::class, 'initial_phase_id');
    }

    public function finalPhase()
    {
        return $this->belongsTo(Phase::class, 'final_phase_id');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
