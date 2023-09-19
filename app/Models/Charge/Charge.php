<?php

namespace App\Models\Charge;

use App\Models\Account;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class Charge extends Model
{
    use HasFactory, HasUuids, SoftDeletes, BelongsToTenant;

    protected static function booted()
    {
        parent::booted();

        static::saving(fn($obj) => $obj->day_charge = $obj->day_charge ?: now()->parse($obj->due_date)->format('d'));
    }

    protected $fillable = [
        'day_charge',
        'group_id',
        'tenant_id',
        'account_id',
        'description',
        'value',
        'charge',
        'category_id',
        'sub_category_id',
        'type',
        'due_date',
        'note',
    ];

    public function charge()
    {
        return $this->morphTo();
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }
}
