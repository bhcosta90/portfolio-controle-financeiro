<?php

namespace Costa\Modules\Charge\Repository;

use Costa\Modules\Charge\Entities\ChargeReceiveEntity;
use Costa\Modules\Charge\Shareds\ValueObjects\ParcelObject;
use Costa\Shareds\Contracts\RepositoryInterface;

interface ChargeReceiveRepositoryInterface extends RepositoryInterface
{
    public function insertChargeWithParcel(ChargeReceiveEntity $entity, ParcelObject $parcel);

    public function getValueTotal(?array $filter = null): float;
}
