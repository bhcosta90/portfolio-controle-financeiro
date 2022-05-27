<?php

namespace Costa\Modules\Charge\Entities;

use Costa\Modules\Charge\Shareds\Enums\Type;

class ChargeReceiveEntity extends ChargeEntity
{
    protected Type $type = Type::DEBIT;
}
