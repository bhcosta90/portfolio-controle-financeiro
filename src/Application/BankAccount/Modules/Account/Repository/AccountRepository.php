<?php

namespace Core\Application\BankAccount\Modules\Account\Repository;

use Core\Application\BankAccount\Modules\Account\Domain\AccountEntity;

interface AccountRepository
{
    public function insert(AccountEntity $entity);

    public function find(string|int $entityId, string $entityType): AccountEntity;

    public function get(string|int $id): AccountEntity;

    public function addValue(string|int $id, float $value): bool;

    public function subValue(string|int $id, float $value): bool;
}
