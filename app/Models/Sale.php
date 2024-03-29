<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Sale extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    // Query Scope
    public function scopeFilter($query, $filters)
    {
        $query->when($filters['client'] ?? null, function($query, $client) {
            $query->where('client_id', $client);
        })->when($filters['voucherType'] ?? null, function($query, $voucherType) {
            $query->where('voucher_type_id', $voucherType);
        })->when($filters['fromDate'] ?? null, function($query, $fromDate) {
            $query->where('date', '>=', $fromDate);
        })->when($filters['toDate'] ?? null, function($query, $toDate) {
            $query->where('date', '<=', $toDate);
        });
    }

    // Relationship with Client
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    // Relationship with PaymentCondition
    public function payment_condition()
    {
        return $this->belongsTo(PaymentConditions::class);
    }

    // Relationship with PaymentMethod
    public function payment_method()
    {
        return $this->belongsTo(PaymentMethods::class);
    }

    // Relationship with VoucherType
    public function voucher_type()
    {
        return $this->belongsTo(VoucherTypes::class);
    }

    // Falta el id_client

    // Relationship many to many with Product
    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot('sublot_id', 'm2_unitary', 'quantity', 'm2_total', 'm2_price', 'subtotal')->withTimestamps();
    }
}
