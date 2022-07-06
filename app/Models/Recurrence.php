<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Recurrence extends Model
{
    use HasFactory, SoftDeletes, Traits\TenantTrait, Traits\UuidTrait;

    public $fillable = [
        'id',
        'days',
        'name',
    ];
}
