<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    // Relationship with TrunkSublot
    public function trunkSublots()
    {
        return $this->hasMany(TrunkSublot::class);
    }

    // Relationship with Sublot
    public function sublots()
    {
        return $this->hasMany(Sublot::class);
    }
}
