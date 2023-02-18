<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeatherApi extends Model
{
    use HasFactory;

    protected $fillable = [
        'temp',
        'rain_prob',
        'rain_mm',
        'humidity',
        'wind_speed',
        'days_in_row',
        'max_conditions'
    ];
}
