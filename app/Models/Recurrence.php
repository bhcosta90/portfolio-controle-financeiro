<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recurrence extends Model
{
    use HasFactory, Traits\TenantTrait;

    public $fillable = [
        'uuid',
        'name',
        'days',
    ];
}
