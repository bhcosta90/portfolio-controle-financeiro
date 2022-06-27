<?php

namespace Core\Financial\Charge\Modules\Payment\UseCases;

use Core\Financial\Charge\Modules\Payment\Domain\PaymentEntity as Entity;
use Core\Financial\Charge\Modules\Payment\Repository\PaymentRepositoryInterface;
use Core\Financial\Relationship\Modules\Company\Repository\CompanyRepositoryInterface;

class UpdateUseCase
{
    public function __construct(
        private PaymentRepositoryInterface $repo,
        private CompanyRepositoryInterface $company,
    ) {
        //
    }

    public function handle(DTO\Update\UpdateInput $input): DTO\Update\UpdateOutput
    {
        /** @var Entity */
        $obj = $this->repo->find($input->id);
        $objCompany = $this->company->find($input->companyId);

        $obj->update(
            value: $input->value,
            company: $objCompany,
            date: $input->date,
            recurrence: $input->recurrenceId,
        );

        $this->repo->update($obj);

        return new DTO\Update\UpdateOutput(
            id: $obj->id(),
            value: $input->value,
            companyId: $input->companyId,
            date: $input->date,
            recurrenceId: $input->recurrenceId,
        );
    }
}
