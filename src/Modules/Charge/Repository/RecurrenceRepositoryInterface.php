<?php

namespace Costa\Modules\Charge\Repository;

use Costa\Modules\Charge\Entities\RecurrenceEntity as Entity;
use Costa\Shareds\Abstracts\EntityAbstract;
use Costa\Shareds\Contracts\PaginationInterface;

interface RecurrenceRepositoryInterface
{
    public function insert(EntityAbstract $entity): Entity;

    public function find(int|string $id): Entity;

    public function update(EntityAbstract $entity): Entity;

    public function delete(int|string $id): bool;

    public function toEntity(object $data): Entity;

    public function pluck(): array;

    public function paginate(
        ?array $filter = null,
        ?array $order = null,
        ?int $page = 1,
        ?int $totalPage = 15
    ): PaginationInterface;
}
