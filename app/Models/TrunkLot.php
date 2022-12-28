<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrunkLot extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    // Relationship with Purchase
    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    // Relationship with TrunkSublot
    public function sublots()
    {
        return $this->hasMany(TrunkSublot::class);
    }

}
