<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lot extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    // Relationship with Task
    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    // Relationship with Sublot
    public function sublots()
    {
        return $this->hasMany(Sublot::class);
    }

    // Relationship with outputProducts
    public function outputProducts()
    {
        return $this->belongsToMany(Product::class, 'product_task_output')->withPivot('id', 'lot_id', 'product_id', 'produced_quantity')->withTimestamps();
    }

}
