<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrunkLot extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    // Relationship with TrunkPurchase
    public function trunk_purchase()
    {
        return $this->belongsTo(TrunkPurchase::class);
    }
}
