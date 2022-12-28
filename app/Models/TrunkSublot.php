<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrunkSublot extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function trunkLot()
    {
        return $this->belongsTo(TrunkLot::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

}
