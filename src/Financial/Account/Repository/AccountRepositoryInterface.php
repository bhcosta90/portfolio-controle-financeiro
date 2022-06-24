<?php

namespace Core\Financial\Account\Repository;

use Core\Financial\Account\Domain\AccountEntity;
use Core\Shared\Abstracts\EntityAbstract;

interface AccountRepositoryInterface
{
    public function insert(AccountEntity $entity): AccountEntity;

    public function entity(object $input, EntityAbstract $entity): AccountEntity;

    public function find(string $id, string $entity): AccountEntity;

    public function delete(AccountEntity $entity): bool;
}
