<?php

namespace Costa\Modules\Charge\Receive\Repository;

use Costa\Modules\Charge\Receive\Entity\ChargeEntity;
use Costa\Modules\Charge\Utils\ValueObject\ParcelObject;
use Costa\Shared\Contracts\RepositoryInterface;

interface ChargeRepositoryInterface extends RepositoryInterface
{
    public function insertWithParcel(ChargeEntity $entity, ParcelObject $parcel): ChargeEntity;

    public function total(?array $filter = null): float;
}
