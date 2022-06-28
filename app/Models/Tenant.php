<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tenant extends Model
{
    use HasFactory, Traits\UuidTrait, SoftDeletes;

    protected $fillable = [
        'id',
        'name',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'users', 'id');
    }

}
