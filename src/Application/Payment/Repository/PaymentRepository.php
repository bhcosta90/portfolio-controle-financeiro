<?php

namespace Core\Application\Payment\Repository;

use Core\Application\Payment\Domain\PaymentEntity;
use Core\Shared\Interfaces\RepositoryInterface;
use Core\Shared\Interfaces\ResultInterface;

interface PaymentRepository extends RepositoryInterface
{
    public function updateStatus(string $date, int $filterStatus, int $status): bool;

    public function getListStatus(int $status, int $totalPage = 50): ResultInterface;

    public function entity(object $input): PaymentEntity;

    public function report(?array $filter = null, ?int $page = 1, ?int $totalPage = 15): ResultInterface;
}
