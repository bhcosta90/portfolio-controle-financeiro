<?php

namespace Core\Financial\Account\Repository;

use Core\Financial\Account\Domain\AccountEntity;

interface AccountRepositoryInterface
{
    public function insert(AccountEntity $entity): bool;

    public function entity(object $input): AccountEntity;

    public function find(string $id, string $entity): AccountEntity;

    public function getById(string $id): AccountEntity;

    public function delete(AccountEntity $entity): bool;

    public function add(AccountEntity $account, float $value): bool;

    public function sub(AccountEntity $account, float $value): bool;
}
