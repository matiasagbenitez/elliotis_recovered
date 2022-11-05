<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    // ---------------------- RELATIONSHIPS ----------------------
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function localities() {
        return $this->hasMany(Locality::class);
    }
}
