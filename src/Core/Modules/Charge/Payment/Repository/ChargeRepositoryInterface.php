<?php

namespace Costa\Modules\Charge\Payment\Repository;

use Costa\Modules\Charge\Payment\Entity\ChargeEntity;
use Costa\Modules\Charge\Utils\ValueObject\ParcelObject;
use Costa\Modules\Charge\Utils\ValueObject\ResumeObject;
use Costa\Shared\Contracts\RepositoryInterface;
use DateTime;

interface ChargeRepositoryInterface extends RepositoryInterface
{
    public function insertWithParcel(ChargeEntity $entity, ParcelObject $parcel): ChargeEntity;

    public function total(?array $filter = null): float;
    
    public function getResumeToday(): ResumeObject;

    public function getAccountPaymentToday(): ResumeObject;
    
    public function getResumeDueDate(): ResumeObject;

    public function getResumeValue(DateTime $date): ResumeObject;
}
