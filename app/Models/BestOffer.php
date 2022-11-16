<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BestOffer extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function tendering()
    {
        return $this->belongsTo(Tendering::class);
    }

    public function offer()
    {
        return $this->belongsTo(Offer::class);
    }

}
