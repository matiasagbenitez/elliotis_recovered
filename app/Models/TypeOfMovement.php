<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeOfMovement extends Model
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
}
