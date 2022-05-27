<?php

namespace Costa\Shareds\Contracts;

use Costa\Shareds\Abstracts\EntityAbstract;

interface RepositoryInterface
{
    public function insert(EntityAbstract $entity): EntityInterface;

    public function find(int|string $id): EntityInterface;

    public function update(EntityAbstract $entity): EntityInterface;

    public function delete(int|string $id): bool;

    public function toEntity(object $data): EntityInterface;

    public function paginate(
        ?array $filter = null,
        ?array $order = null,
        ?int $page = 1,
        ?int $totalPage = 15
    ): PaginationInterface;
}
