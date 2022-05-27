<?php

namespace Costa\Modules\Charge\UseCases\Receive;

use Costa\Modules\Charge\Repository\ChargeReceiveRepositoryInterface;
use Costa\Modules\Charge\UseCases\Charge\ChargeDeleteUseCase;
use Costa\Shareds\Contracts\TransactionContract;

class ReceiveDeleteUseCase extends ChargeDeleteUseCase
{
    public function __construct(
        protected ChargeReceiveRepositoryInterface $repo,
        protected TransactionContract $transaction,
    ) {
        //
    }
}
