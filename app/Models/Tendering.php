<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tendering extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    // Many to many relationship with products
    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot('quantity', 'tn_total', 'tn_price', 'subtotal')->withTimestamps();
    }

    // Relationship with Hash
    public function hashes()
    {
        return $this->hasMany(Hash::class);
    }

    // Relationship with BestOffer
    public function bestOffer()
    {
        return $this->hasOne(BestOffer::class);
    }
}
