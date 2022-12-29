<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sublot extends Model
{
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
}
