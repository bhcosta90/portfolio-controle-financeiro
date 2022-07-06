<?php

namespace Core\Application\Charge\Modules\Receive\Repository;

use Core\Application\Charge\Modules\Receive\Domain\ReceiveEntity;
use Core\Shared\Interfaces\RepositoryInterface;
use Core\Shared\ValueObjects\ParcelObject;

interface ChargeReceiveRepository extends RepositoryInterface
{
    public function insertParcel(ReceiveEntity $entity, ParcelObject $parcel): bool;
}
