<?php

namespace Core\Financial\Charge\Modules\Payment\UseCases;

use Core\Financial\BankAccount\Domain\BankAccountEntity as Entity;
use Core\Financial\Charge\Modules\Payment\Repository\PaymentRepositoryInterface;
use Core\Shared\UseCases\Find\FindInput;

class FindUseCase
{
    public function __construct(
        private PaymentRepositoryInterface $repo,
    ) {
        //
    }

    public function handle(FindInput $input): DTO\Find\FindOutput
    {
        /** @var Entity */
        $obj = $this->repo->find($input->id);

        return new DTO\Find\FindOutput(
            id: $obj->id(),
            value: $obj->value,
            companyId: $obj->company->id(),
            date: $obj->date->format('Y-m-d'),
            recurrenceId: $obj->recurrence?->id(),
        );
    }
}
