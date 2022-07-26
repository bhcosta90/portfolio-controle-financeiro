<?php

namespace Core\Application\Charge\Modules\Receive\Repository;

use Core\Application\Charge\Modules\Receive\Domain\ReceiveEntity;
use Core\Shared\Interfaces\RepositoryInterface;
use Core\Shared\ValueObjects\ParcelObject;
use DateTime;

interface ReceiveRepository extends RepositoryInterface
{
    public function insertParcel(ReceiveEntity $entity, ParcelObject $parcel): bool;

    public function filterByDate(DateTime $start, DateTime $end, int $type);

    public function filterByCustomerName(string $name);

    public function total(array $filter): float;
}
