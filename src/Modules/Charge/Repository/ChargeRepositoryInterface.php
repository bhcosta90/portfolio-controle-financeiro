<?php

namespace Costa\Modules\Charge\Repository;

use Costa\Modules\Charge\Shareds\ValueObjects\ResumeObject;
use DateTime;

interface ChargeRepositoryInterface
{
    public function getAccountPaymentToday(): ResumeObject;
    
    public function getResumeDueDate(): ResumeObject;

    public function getResumeValue(DateTime $date): ResumeObject;
}
