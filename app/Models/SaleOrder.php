<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class SaleOrder extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    // Many to many relationship with products
    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot('m2_unitary', 'quantity', 'm2_total', 'm2_price', 'subtotal')->withTimestamps();
    }

    // Relationship with User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship with Client
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

}
