<?php

namespace App\Repository\Eloquent;

use App\Models\Tenant;
use Core\Application\Tenant\Domain\TenantEntity;
use Core\Application\Tenant\Repository\TenantRepository;
use Illuminate\Support\Facades\DB;

class TenantEloquent implements TenantRepository
{
    private $table;

    public function __construct(
        protected Tenant $model,
    ) {
        $this->table = with(new $this->model)->getTable();
    }

    public function find(string|int $id): TenantEntity
    {
        $obj = DB::table($this->table)
            ->select('tenants.*', 'accounts.value', 'accounts.id as account_id')
            ->where('tenants.id', $id)
            ->join('accounts', fn ($q) => $q->on('accounts.entity_id', '=', 'tenants.id')
                ->where('accounts.entity_type', TenantEntity::class))
            ->first();

        return TenantEntity::create(
            $obj->value,
            $obj->id,
            $obj->account_id,
            $obj->created_at
        );
    }

    public function addValue(string|int $id, float $value): bool
    {
        return DB::table($this->table)->where('id', $id)->increment('value', $value);
    }

    public function subValue(string|int $id, float $value): bool
    {
        return DB::table($this->table)->where('id', $id)->decrement('value', $value);
    }
}
