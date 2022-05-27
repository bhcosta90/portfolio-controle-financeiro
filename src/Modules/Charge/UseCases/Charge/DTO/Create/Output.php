<?php

namespace Costa\Modules\Charge\UseCases\Charge\DTO\Create;

use Costa\Modules\Charge\Entities\ChargePaymentEntity;
use DateTime;

class Output
{
    /** @var ChargePaymentEntity $charges[] */
    public function __construct(
        public array $charges,
    ) {
        //
    }
}
