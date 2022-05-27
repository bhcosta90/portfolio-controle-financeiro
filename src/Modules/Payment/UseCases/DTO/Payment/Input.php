<?php

namespace Costa\Modules\Payment\UseCases\DTO\Payment;

use Costa\Modules\Account\Entities\AccountEntity;
use Costa\Modules\Account\Entities\BankEntity;
use Costa\Modules\Payment\Shareds\Enums\Type;
use Costa\Shareds\ValueObjects\Input\InputValueObject;
use Costa\Shareds\ValueObjects\ModelObject;

class Input
{
    public function __construct(
        public Type $type,
        public InputValueObject $value,
        public ModelObject $account,
        public int|string|null $bank,
        public array $accounts = [],
    ) {
        //
    }
}
