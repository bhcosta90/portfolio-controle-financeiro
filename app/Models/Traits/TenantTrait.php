<?php

namespace App\Models\Traits;

use App\Models\Tenant;
use App\Models\User;
use Exception;

trait TenantTrait
{
    public static function bootTenantTrait()
    {
        static::addGlobalScope('tenant_id', function ($builder) {
            if (!app()->runningInConsole() && ($id = self::getTenantId())) {
                $table = with(new static)->getTable();
                $builder->whereIn($table . '.tenant_id', (array)$id);
            }
        });
    }

    private static function getTenantId()
    {
        if (app()->environment('testing')) {
            if (empty($tenant = Tenant::first())) {
                $tenant = Tenant::factory()->create();
            }
            return $tenant->id;
        }

        return request()->user()->tenant_id;
    }
}
