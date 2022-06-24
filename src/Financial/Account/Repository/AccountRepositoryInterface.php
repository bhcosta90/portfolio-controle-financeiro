<?php

namespace Core\Financial\Account\Repository;

use Core\Financial\Account\Domain\AccountEntity;

interface AccountRepositoryInterface
{
    public function insert(AccountEntity $entity): AccountEntity;

    public function entity(object $input): AccountEntity;
}
