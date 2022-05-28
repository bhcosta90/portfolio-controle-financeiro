<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Account extends Model
{
    use HasFactory, Traits\UuidTrait, SoftDeletes;

    public $fillable = [
        'id',
        'entity_type',
        'entity_id',
        'value'
    ];
}
