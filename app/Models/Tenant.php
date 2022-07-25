<?php

namespace App\Models;

use Core\Application\Tenant\Domain\TenantEntity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tenant extends Model
{
    use HasFactory, Traits\UuidTrait, SoftDeletes;

    public $fillable = [
        'id'
    ];

    public static function booted()
    {
        static::created(fn ($obj) => Account::create([
            'id' => str()->uuid(),
            'tenant_id' => $obj->id,
            'entity_id' => $obj->id,
            'entity_type' => TenantEntity::class,
            'value' => 0,
        ]));
    }

    public function scopeIdAccount($query)
    {
        $objAccount = $query->join(
            'accounts',
            fn ($query) => $query
                ->on('accounts.entity_id', '=', 'tenants.id')
                ->where('accounts.entity_type', TenantEntity::class)
        )->first();
        return Account::find($objAccount->id)->id;
    }
}
