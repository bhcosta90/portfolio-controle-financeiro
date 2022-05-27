<?php

namespace Costa\Modules\Charge\UseCases\Receive;

use Costa\Modules\Charge\Repository\ChargeReceiveRepositoryInterface;
use Costa\Modules\Charge\UseCases\Charge\ChargeListUseCase;

class ReceiveListUseCase extends ChargeListUseCase
{
    public function __construct(
        protected ChargeReceiveRepositoryInterface $repo
    ) {
        //
    }
}
