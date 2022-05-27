<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Charge extends Model
{
    use HasFactory, Traits\TenantTrait, SoftDeletes;

    public $fillable = [
        'relationship_type',
        'relationship_id',
        'recurrence_id',
        'parcel_total',
        'parcel_actual',
        'title',
        'description',
        'uuid',
        'group_uuid',
        'base',
        'charge_id',
        'charge_type',
        'date_start',
        'date_finish',
        'date_due',
        'status',
        'value_charge',
        'value_pay',
    ];
}
