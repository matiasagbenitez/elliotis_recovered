<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductType extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    // Relationship with Measure
    public function measure()
    {
        return $this->belongsTo(Measure::class);
    }

    // Relationship with Unity
    public function unity()
    {
        return $this->belongsTo(Unity::class);
    }

    // Relationship with ProductName
    public function product_name()
    {
        return $this->belongsTo(ProductName::class);
    }

    // Relationship with Product
    public function products()
    {
        return $this->hasMany(Product::class);
    }

}
