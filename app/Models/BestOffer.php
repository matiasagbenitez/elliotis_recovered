<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
class BestOffer extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
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
