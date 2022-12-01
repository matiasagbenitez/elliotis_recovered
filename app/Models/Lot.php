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
}
