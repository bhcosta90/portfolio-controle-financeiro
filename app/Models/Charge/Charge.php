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

    protected $fillable = [
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
