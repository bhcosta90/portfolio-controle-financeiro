<?php

namespace App\Repository\Eloquent;

use App\Models\Account;
use Core\Financial\Account\Repository\AccountRepositoryInterface;
use Core\Financial\Account\Domain\AccountEntity;

class AccountEloquentRepository implements AccountRepositoryInterface
{
    public function __construct(
        protected Account $model,
    ) {
        //  
    }

    public function insert(AccountEntity $entity): AccountEntity
    {
        $obj = $this->model->create([
            'id' => $entity->id(),
            'value' => $entity->value,
            'entity_id' => $entity->entity_id,
            'entity_type' => $entity->entity_type,
        ]);

        return $this->entity($obj);
    }

    public function entity(object $input): AccountEntity
    {
        return AccountEntity::create(
            entity_type: $input->entity_type,
            entity_id: $input->entity_id,
            value: $input->value,
        );
    }
}
