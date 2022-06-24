<?php

namespace App\Repository\Eloquent;

use App\Models\Account;
use Core\Financial\Account\Contracts\AccountInterface;
use Core\Financial\Account\Repository\AccountRepositoryInterface;
use Core\Financial\Account\Domain\AccountEntity;
use Core\Shared\Abstracts\EntityAbstract;

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
            'entity_id' => $entity->entity->id(),
            'entity_type' => get_class($entity->entity),
        ]);

        return $this->entity($obj, $entity->entity);
    }

    /**
     * @param object $input
     * @param EntityAbstract $entity
     * @return AccountEntity
     */
    public function entity(object $input, AccountInterface $entity): AccountEntity
    {
        return AccountEntity::create(
            entity: $entity,
            value: $input->value,
        );
    }
}
