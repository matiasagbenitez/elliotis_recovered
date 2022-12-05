<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movement extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function typeOfMovement()
    {
        return $this->belongsTo(TypeOfMovement::class);
    }

    public function movementStatus()
    {
        return $this->belongsTo(MovementStatus::class);
    }
}
