<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Measure extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function widthRelation()
    {
        return $this->belongsTo(Inch::class, 'width');
    }

    public function lengthRelation()
    {
        return $this->belongsTo(Feet::class, 'length');
    }
}
