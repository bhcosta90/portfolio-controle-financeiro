<?php

namespace Core\Application\Charge\Modules\Payment\Repository;

use Core\Application\Charge\Modules\Payment\Domain\PaymentEntity;
use Core\Shared\Interfaces\RepositoryInterface;
use Core\Shared\ValueObjects\ParcelObject;
use DateTime;

interface PaymentRepository extends RepositoryInterface
{
    public function insertParcel(PaymentEntity $entity, ParcelObject $parcel): bool;

    public function filterByDate(DateTime $start, DateTime $end, int $type);

    public function filterByCompanyName(string $name);
}
