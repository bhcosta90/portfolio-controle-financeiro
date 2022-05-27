<?php

namespace Costa\Modules\Charge\UseCases\Receive;

use Costa\Modules\Charge\Repository\ChargeReceiveRepositoryInterface;
use Costa\Modules\Charge\UseCases\Charge\ChargeFindUseCase;

class ReceiveFindUseCase extends ChargeFindUseCase
{
    public function __construct(protected ChargeReceiveRepositoryInterface $repo)
    {
        //
    }
}
