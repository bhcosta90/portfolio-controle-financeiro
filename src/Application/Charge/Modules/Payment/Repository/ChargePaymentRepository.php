<?php

namespace Core\Application\Charge\Modules\Payment\Repository;

use Core\Application\Charge\Modules\Payment\Domain\PaymentEntity;
use Core\Shared\Interfaces\RepositoryInterface;
use Core\Shared\ValueObjects\ParcelObject;

interface ChargePaymentRepository extends RepositoryInterface
{
    public function insertParcel(PaymentEntity $entity, ParcelObject $parcel): bool;
}
