<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Phase extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function sublots()
    {
        return $this->hasMany(Sublot::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
