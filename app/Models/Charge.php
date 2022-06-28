<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Charge extends Model
{
    use HasFactory, Traits\UuidTrait, Traits\TenantTrait, SoftDeletes;

    public $fillable = [
        'id',
        'recurrence_id',
        'relationship_id',
        'relationship_type',
        'entity',
        'group_id',
        'status',
        'type',
        'value_charge',
        'value_pay',
        'date',
    ];
}
