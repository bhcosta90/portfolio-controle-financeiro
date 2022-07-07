<?php

namespace Core\Application\Charge\Modules\Payment\Services;

use Core\Application\Charge\Modules\Payment\Domain\PaymentEntity as Entity;
use Core\Application\Charge\Modules\Payment\Repository\ChargePaymentRepository as Repo;
use Core\Application\Charge\Modules\Recurrence\Repository\RecurrenceRepository;
use Core\Application\Relationship\Modules\Company\Repository\CompanyRepository as RelationshipRepository;
use Core\Application\Relationship\Shared\Exceptions\RelationshipException;
use Exception;

class UpdateService
{
    public function __construct(
        private Repo                   $repository,
        private RelationshipRepository $relationship,
        private RecurrenceRepository   $recurrence,
    )
    {
        //
    }

    public function handle(DTO\Update\Input $input): DTO\Update\Output
    {
        /** @var Entity */
        $entity = $this->repository->find($input->id);

        if ($input->company != (string)$entity->company->id && !$this->relationship->exist($input->company)) {
            throw new RelationshipException('Company not found');
        }

        if (
            $input->recurrence
            && $input->recurrence != (string)$entity->recurrence && !$this->recurrence->exist($input->recurrence)
        ) {
            throw new Exception('Recurrence not found');
        }

        $entity->update(
            title: $input->title,
            resume: $input->resume,
            company: $input->company,
            recurrence: $input->recurrence,
            value: $input->value,
            date: $input->date
        );

        $this->repository->update($entity);

        return new DTO\Update\Output(
            title: $entity->title->value,
            resume: $entity->resume?->value,
            company: (string)$entity->company->id,
            recurrence: (string)$entity->recurrence,
            value: $entity->value->value,
            date: $entity->date->format('Y-m-d'),
            group: (string)$entity->group,
            id: $entity->id(),
        );
    }
}
