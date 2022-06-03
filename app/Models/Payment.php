<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory, Traits\UuidTrait;

    public $fillable = [
        'id',
        'charge_id',
        'account_from_id',
        'account_to_id',
        'relationship_id',
        'date_schedule',
        'value_transaction',
        'value_payment',
        'type',
        'completed',
    ];
}
