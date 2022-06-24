<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

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
