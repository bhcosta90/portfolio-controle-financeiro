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
        'value',
        'entity_id',
        'entity_type',
    ];

    public function entity()
    {
        return $this->morphTo();
    }
}
