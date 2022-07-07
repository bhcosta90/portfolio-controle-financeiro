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

        return self::getUser()->tenant_id;
    }

    private static function getUser()
    {
        if (app()->environment('testing')) {
            if (empty($user = User::first())) {
                $user = User::factory()->create();
            }
            return $user;
        }

        if (($user = request()->user()) || ($user = auth()->user())) {
            return $user;
        }

        throw new Exception('User not logging');
    }

    private static function getTenantAll()
    {
        return self::getUser()->tenants()->pluck('id')->toArray();
    }
}
