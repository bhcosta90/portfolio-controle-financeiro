<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Builder;

trait TenantTrait
{
    public static function bootTenantTrait()
    {
        self::creating(fn ($obj) => $obj->tenant_id = auth()->user()->tenant_id);

        if (auth()->check()) {
            $table = with(new static)->getTable();
            static::addGlobalScope('tenants', fn (Builder $builder) => $builder->where($table . '.tenant_id', auth()->user()->tenant_id));
        }
    }
}
