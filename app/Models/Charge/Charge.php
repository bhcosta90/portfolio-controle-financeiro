<?php

namespace App\Models\Charge;

use App\Models\Account;
use App\Models\Category;
use App\Models\Enum\Charge\TypeEnum;
use App\Services\ChargeService;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

/**
 * @property string $group_id
 */
class Charge extends Model
{
    use HasFactory, HasUuids, SoftDeletes, BelongsToTenant;

    protected $casts = [
        'type' => TypeEnum::class,
    ];

    protected $fillable = [
        'day_charge',
        'group_id',
        'is_parcel',
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

    protected static function booted()
    {
        parent::booted();

        static::saving(fn ($obj) => $obj->day_charge = $obj->day_charge ?: now()->parse($obj->due_date)->format('d'));
    }

    public function charge(): MorphTo
    {
        return $this->morphTo();
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public function payed(bool $payed): void
    {
        $this->is_payed = $payed;
        $this->save();

        $service = new ChargeService($this->charge);
        $service->payed($this);
    }

    public function isPayed(): Attribute
    {
        return Attribute::make(get: fn ($value) => (bool) $value);
    }

    public function deleteAll()
    {
        return self::query()->where('group_id', $this->group_id)->delete();
    }

    public function deleteThisAndNext()
    {
        return self::query()->where('group_id', $this->group_id)->where('due_date', '>=', $this->due_date)->delete();
    }

    public function deleteOnlyThis(): bool
    {
        $this->is_deleted = true;
        $this->save();
        return $this->delete();
    }
}
