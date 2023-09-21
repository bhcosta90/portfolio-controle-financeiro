<?php

namespace App\Models\Charge;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class Payment extends Model
{
    use HasFactory, HasUuids, SoftDeletes, BelongsToTenant;

    protected $with = ['charge'];

    public function charge(): MorphOne
    {
        return $this->morphOne(Charge::class, 'charge');
    }
}
