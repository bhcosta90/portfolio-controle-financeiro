<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Charge extends Model
{
    use HasFactory, Traits\UuidTrait, SoftDeletes;

    public $fillable = [
        'id',
        'recurrence_id',
        'relationship_id',
        'relationship_type',
        'entity',
        'uuid',
        'title',
        'description',
        'date_start',
        'date_finish',
        'date_due',
        'parcel_total',
        'parcel_actual',
        'status',
        'value_charge',
        'value_pay',
    ];
}
