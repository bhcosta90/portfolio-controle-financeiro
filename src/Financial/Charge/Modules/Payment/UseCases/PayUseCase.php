<?php

namespace Core\Financial\Charge\Modules\Payment\UseCases;

use Core\Financial\Charge\Modules\Payment\Domain\PaymentEntity as Entity;
use Core\Financial\Charge\Modules\Payment\Repository\PaymentRepositoryInterface;

class PayUseCase
{
    public function __construct(
        private PaymentRepositoryInterface $repo,
    ) {
        //
    }

    public function handle(DTO\Pay\PayInput $input): DTO\Pay\PayOutput
    {
        /** @var Entity */
        $obj = $this->repo->find($input->id);
        $obj->pay($input->pay, $input->value);
        $this->repo->update($obj);

        return new DTO\Pay\PayOutput(
            id: $obj->id(),
            value: $input->value,
            pay: $input->pay,
        );
    }
}
