<?php

namespace Core\Application\Tenant\Repository;

use Core\Application\Tenant\Domain\TenantEntity;

interface TenantRepository
{
    public function find(string|int $id): TenantEntity;

    public function addValue(string|int $id, float $value): bool;

    public function subValue(string|int $id, float $value): bool;
}
