<?php

namespace Costa\Modules\Charge\Entities;

use Costa\Modules\Charge\Shareds\Enums\Type;

class ChargePaymentEntity extends ChargeEntity
{
    protected Type $type = Type::CREDIT;
}
