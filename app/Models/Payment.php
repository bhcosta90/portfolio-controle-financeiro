<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory, Traits\TenantTrait, SoftDeletes;

    public $fillable = [
        'uuid',
        'charge_id',
        'bank_id',
        'type',
        'account_id',
        'relationship_id',
        'date_schedule',
        'value_transaction',
        'value_payment',
        'completed',
    ];
}
