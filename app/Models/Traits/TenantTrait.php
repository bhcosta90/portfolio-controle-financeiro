<?php

namespace App\Models\Traits;

use Exception;

trait TenantTrait
{
    public static function bootTenantTrait()
    {
        $tenant = self::getTenantId();

        static::addGlobalScope('tenant_id', function ($builder) {
            $builder->whereIn('tenant_id', (array) self::getTenantId());
        });

        static::creating(fn ($model) => $model->tenant_id = $tenant);
    }

    private static function getTenantId()
    {
        return self::getUser()->tenant_id;
    }

    private static function getTenantAll()
    {
        return self::getUser()->tenants()->pluck('id')->toArray();
    }

    private static function getUser()
    {
        if ($user = request()->user()) {
            return $user;
        }

        throw new Exception('User not logging');
    }
}
