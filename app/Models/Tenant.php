<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    use HasFactory;

    public $fillable = [
        'uuid',
        'value',
    ];

    public $casts = [
        'value' => "float"
    ];
}
