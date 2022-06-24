<?php

namespace Core\Financial\Account\Repository;

use Core\Financial\Account\Contracts\AccountInterface;
use Core\Financial\Account\Domain\AccountEntity;
use Core\Shared\Abstracts\EntityAbstract;

interface AccountRepositoryInterface
{
    public function insert(AccountEntity $entity): AccountEntity;

    public function entity(object $input, AccountInterface $entity): AccountEntity;
}
