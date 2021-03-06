<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Charge extends Model
{
    use HasFactory, Traits\UuidTrait, SoftDeletes, Traits\TenantTrait;

    public $fillable = [
        'id',
        'tenant_id',
        'group_id',
        'recurrence_id',
        'relationship_id',
        'relationship_type',
        'entity',
        'title',
        'resume',
        'value_charge',
        'value_pay',
        'type',
        'status',
        'date',
        'parcel_actual',
        'parcel_total',
    ];
}
