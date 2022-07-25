<?php

namespace Core\Application\Transaction\Repository;

use Core\Application\Transaction\Domain\TransactionEntity;
use Core\Shared\Interfaces\PaginationInterface;
use Core\Shared\Interfaces\ResultInterface;
use DateTime;

interface TransactionRepository
{
    public function insert(TransactionEntity $entity): bool;

    public function update(TransactionEntity $entity): bool;

    public function find(string|int $id): TransactionEntity;

    public function filterByDate(DateTime $start, DateTime $end);

    public function filterByName(string $name);

    public function getTransactionInDate(DateTime $date, int $limit, int $page): ResultInterface;

    public function toEntity(object $object): TransactionEntity;

    public function paginate(
        ?array $filter = null,
        ?int $page = 1,
        ?int $totalPage = 15
    ): PaginationInterface;
}
