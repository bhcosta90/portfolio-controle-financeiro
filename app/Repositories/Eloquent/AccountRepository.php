<?php

namespace App\Repositories\Eloquent;

use App\Models\Account;
use App\Models\Tenant;
use Costa\Modules\Account\Entities\AccountEntity;
use Costa\Modules\Account\Repository\AccountRepositoryInterface;
use Costa\Shareds\Abstracts\EntityAbstract;
use Costa\Shareds\ValueObjects\ModelObject;

class AccountRepository implements AccountRepositoryInterface
{
    public function __construct(private Account $model, private Tenant $tenant)
    {
        //
    }

    public function insert(EntityAbstract $entity): AccountEntity
    {
        $obj = $this->model->create([
            'model_type' => $entity->model->type,
            'model_id' => $entity->model->id,
            'value' => $entity->value,
        ]);

        return new AccountEntity(
            model: $entity->model,
            value: $obj->value,
        );
    }

    public function find(ModelObject $model): AccountEntity
    {
        $obj = $this->model->where('model_id', $model->id)
            ->where('model_type', $model->type)
            ->firstOrFail();
        
        return new AccountEntity(
            model: new ModelObject($obj->model_id, $obj->model_type),
            value: $obj->value,
            increment: $obj->id,
        );
    }

    public function update(EntityAbstract $entity): AccountEntity
    {
        $objAccount = $this->model->find($entity->increment);
        $objAccount->value = $entity->value;
        $objAccount->save();

        return new AccountEntity(
            model: new ModelObject($objAccount->model_id, $objAccount->model_type),
            value: $objAccount->value,
            increment: $objAccount->id,
        );
    }

    public function addValue(EntityAbstract $entity, float $value): AccountEntity
    {
        $objAccount = $this->model->find($entity->increment);
        $objAccount->increment('value', $value);
        
        return new AccountEntity(
            model: new ModelObject($objAccount->model_id, $objAccount->model_type),
            value: $objAccount->value,
            increment: $objAccount->id,
        );
    }

    public function subValue(EntityAbstract $entity, float $value): AccountEntity
    {
        $objAccount = $this->model->find($entity->increment);
        $objAccount->decrement('value', $value);
        
        return new AccountEntity(
            model: new ModelObject($objAccount->model_id, $objAccount->model_type),
            value: $objAccount->value,
            increment: $objAccount->id,
        );
    }
}
