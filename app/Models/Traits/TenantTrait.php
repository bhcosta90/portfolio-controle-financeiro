<?php

namespace App\Models\Traits;

use App\Models\User;
use Exception;

trait TenantTrait
{
    public static function bootTenantTrait()
    {
        static::addGlobalScope('tenant_id', function ($builder) {
            if (!app()->runningInConsole() && ($id = self::getTenantId())) {
                $table = with(new static)->getTable();
                $builder->whereIn($table . '.tenant_id', (array) $id);
            }
        });

        static::creating(function ($model) {
            if (empty($model->tenant_id)) {
                $model->tenant_id = self::getTenantId();
            }
        });
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
        if (app()->environment('testing')) {
            if (empty($user = User::first())) {
                $user = User::factory()->create();
            }
            return $user;
        }

        if ($user = request()->user()) {
            return $user;
        }

        throw new Exception('User not logging');
    }
}
