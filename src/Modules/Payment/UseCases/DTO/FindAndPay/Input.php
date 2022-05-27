<?php

namespace Costa\Modules\Payment\UseCases\DTO\FindAndPay;

use Costa\Modules\Payment\Shareds\Enums\Type;
use Costa\Shareds\ValueObjects\Input\InputValueObject;
use Costa\Shareds\ValueObjects\ModelObject;
use Costa\Shareds\ValueObjects\UuidObject;

class Input
{
    public function __construct(
        public UuidObject $id,
        public Type $type,
        public InputValueObject $value,
        public ModelObject $account,
        public array $accounts = []
    ) {
        //
    }
}
