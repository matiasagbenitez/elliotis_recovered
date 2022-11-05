<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Locality extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    // ---------------------- RELATIONSHIPS ----------------------
    public function province()
    {
        return $this->belongsTo(Province::class);
    }
}
