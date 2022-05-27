<?php

namespace Costa\Modules\Charge\Repository;

use Costa\Modules\Charge\Entities\ChargePaymentEntity;
use Costa\Modules\Charge\Shareds\ValueObjects\ParcelObject;
use Costa\Modules\Charge\Shareds\ValueObjects\ResumeObject;
use Costa\Shareds\Contracts\RepositoryInterface;

interface ChargePaymentRepositoryInterface extends RepositoryInterface
{
    public function insertChargeWithParcel(ChargePaymentEntity $entity, ParcelObject $parcel);

    public function getValueTotal(?array $filter = null): float;

    public function getResumeToday(): ResumeObject;
}
