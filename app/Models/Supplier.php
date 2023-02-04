<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Supplier extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    // Relationships
    public function iva_condition()
    {
        return $this->belongsTo(IvaCondition::class);
    }

    public function locality()
    {
        return $this->belongsTo(Locality::class);
    }

    // Relation with Purchase
    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    // Relation with Hash
    public function hashes()
    {
        return $this->hasMany(Hash::class);
    }
}
