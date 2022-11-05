<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    // Query Scope
    public function scopeFilter($query, $filters)
    {
        $query->when($filters['supplier'] ?? null, function($query, $supplier) {
            $query->where('supplier_id', $supplier);
        })->when($filters['voucherType'] ?? null, function($query, $voucherType) {
            $query->where('voucher_type_id', $voucherType);
        })->when($filters['fromDate'] ?? null, function($query, $fromDate) {
            $query->where('date', '>=', $fromDate);
        })->when($filters['toDate'] ?? null, function($query, $toDate) {
            $query->where('date', '<=', $toDate);
        });
    }

    // Relationship with Supplier
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
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

    // Falta el id_supplier

    // Relationship many to many with Product
    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot('quantity', 'price', 'subtotal')->withTimestamps();
    }
}
