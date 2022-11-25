<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrunkPurchase extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    // Relationship with Purchase
    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    // Relationship with TrunkLot
    public function trunk_lots()
    {
        return $this->hasMany(TrunkLot::class);
    }
}
