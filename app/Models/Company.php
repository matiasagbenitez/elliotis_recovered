<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slogan',
        'email',
        'phone',
        'address',
        'cp',
        'logo'
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
