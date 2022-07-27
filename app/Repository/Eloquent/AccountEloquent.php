<?php

namespace App\Repository\Eloquent;

use App\Models\Account;
use Core\Application\BankAccount\Modules\Account\Domain\AccountEntity;
use Core\Application\BankAccount\Modules\Account\Repository\AccountRepository;
use Core\Shared\Abstracts\EntityAbstract;
use Illuminate\Support\Facades\DB;

class AccountEloquent implements AccountRepository
{
    private $table;

    public function __construct(
        protected Account $model,
    ) {
        $this->table = with(new $this->model)->getTable();
    }

    public function insert(EntityAbstract $entity): bool
    {
        return (bool) Account::create([
            'id' => $entity->id(),
            'tenant_id' => $entity->tenant,
            'entity_type' => $entity->entity->type,
            'entity_id' => $entity->entity->id,
            'value' => $entity->value,
        ]);
    }

    public function find(string|int $entityId, string $entityType): AccountEntity
    {
        $obj = DB::table($this->table)
            ->select()
            ->where('entity_id', $entityId)
            ->where('entity_type', $entityType)
            ->first();

        return AccountEntity::create(
            $obj->tenant_id,
            $obj->entity_id,
            $obj->entity_type,
            $obj->value,
            $obj->id,
            $obj->created_at
        );
    }

    public function get(string|int $id): AccountEntity
    {
        $obj = DB::table($this->table)
            ->select()
            ->where('id', $id)
            ->first();

        return AccountEntity::create(
            $obj->tenant_id,
            $obj->entity_id,
            $obj->entity_type,
            $obj->value,
            $obj->id,
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
