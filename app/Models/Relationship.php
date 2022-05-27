<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Relationship extends Model
{
    use HasFactory, SoftDeletes, Traits\TenantTrait;

    public $fillable = [
        'uuid',
        'name',
        'relationship_id',
        'relationship_type',
    ];
}
