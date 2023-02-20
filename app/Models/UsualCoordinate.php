<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsualCoordinate extends Model
{
    use HasFactory;

    protected $fillable = [
        'city',
        'lat',
        'lon',
    ];
}
