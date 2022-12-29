<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    // Relationship with ProductType
    public function productType()
    {
        return $this->belongsTo(ProductType::class);
    }

    // Relationship with WoodType
    public function woodType()
    {
        return $this->belongsTo(WoodType::class);
    }

    // Relationship with IvaType
    public function ivaType()
    {
        return $this->belongsTo(IvaType::class);
    }

    // Relationship many to many with Purchase
    public function purchases()
    {
        return $this->belongsToMany(Purchase::class);
    }

    // Relationship many to many with Sale
    public function sales()
    {
        return $this->belongsToMany(Sale::class);
    }

    // Relationship many with Price
    public function prices()
    {
        return $this->hasMany(Price::class);
    }

    // Relationship many with SaleOrder
    public function saleOrders()
    {
        return $this->belongsToMany(SaleOrder::class);
    }

    // Relationship many with PurchaseOrder
    public function purchaseOrders()
    {
        return $this->belongsToMany(PurchaseOrder::class);
    }

    // Relationship many with Tendering
    public function tenderings()
    {
        return $this->belongsToMany(Tendering::class);
    }

    // Relationship many with Offer
    public function offers()
    {
        return $this->belongsToMany(Offer::class);
    }

    // Relationship many with TrunkSublot
    public function trunkSublots()
    {
        return $this->hasMany(TrunkSublot::class);
    }

    // Relationship many with FollowingProduct
    public function followingProducts()
    {
        return $this->belongsToMany(Product::class, 'following_products', 'base_product_id', 'following_product_id')->withPivot('final_product')->withTimestamps();
    }

    public function previousProduct()
    {
        return $this->hasOneThrough(
            Product::class,
            PreviousProduct::class,
            'product_id',
            'id',
            'id',
            'previous_product_id',
        );
    }

    public function sublots()
    {
        return $this->hasMany(Sublot::class);
    }
}
