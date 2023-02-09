<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Lot extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function sublots()
    {
        return $this->hasMany(Sublot::class);
    }
}
