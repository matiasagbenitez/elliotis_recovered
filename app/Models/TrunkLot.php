<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrunkLot extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    // Relationship with TrunkPurchase
    public function trunk_purchase()
    {
        return $this->belongsTo(TrunkPurchase::class);
    }

    // Relationship with Product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Relationship with tasks
    public function tasks()
    {
        return $this->belongsToMany(Task::class)->withPivot('id', 'task_id', 'trunk_lot_id', 'consumed_quantity')->withTimestamps();
    }
}
