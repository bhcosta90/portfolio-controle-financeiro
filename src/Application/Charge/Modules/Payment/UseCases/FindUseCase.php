<?php

namespace Core\Application\Charge\Modules\Payment\UseCases;

use Core\Application\Charge\Modules\Payment\Domain\PaymentEntity as Entity;
use Core\Application\Charge\Modules\Payment\Repository\PaymentRepository as Repo;
use Core\Application\Relationship\Modules\Company\Repository\CompanyRepository;
use Core\Shared\UseCases\Find\FindInput;

class FindUseCase
{
    public function __construct(
        private Repo $repository,
        private CompanyRepository $relationship,
    ) {
        //
    }

    public function handle(FindInput $input): DTO\Find\Output
    {
        /** @var Entity */
        $entity = $this->repository->find($input->id);
        $relationship = $this->relationship->find($entity->company->id);

        return new DTO\Find\Output(
            title: $entity->title->value,
            resume: $entity->resume?->value,
            company: (string)$entity->company->id,
            companyName: (string)$relationship->name->value,
            recurrence: (string)$entity->recurrence,
            value: $entity->value->value,
            pay: $entity->pay->value,
            date: $entity->date->format('Y-m-d'),
            group: (string)$entity->group,
            id: $entity->id(),
        );
    }
}
