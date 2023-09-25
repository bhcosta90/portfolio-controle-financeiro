<?php

namespace App\Models;

use App\Models\Charge\Charge;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Extract extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'value',
        'account_id',
        'charge_type',
    ];

    protected static function booted()
    {
        parent::booted();

        static::creating(fn ($obj) => $obj->user_id = $obj->user_id ?: auth()->user()->id);
    }

    public function model() {
        return $this->morphOne(Charge::class, 'model');
    }
}
