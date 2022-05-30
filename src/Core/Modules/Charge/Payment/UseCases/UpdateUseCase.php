<?php

namespace Costa\Modules\Charge\Payment\UseCases;

use Costa\Modules\Charge\Payment\Entity\ChargeEntity;
use Costa\Modules\Charge\Payment\Repository\ChargeRepositoryInterface;
use Costa\Modules\Recurrence\Repository\RecurrenceRepositoryInterface;
use Costa\Modules\Relationship\Supplier\Repository\SupplierRepositoryInterface;
use Costa\Shared\Contracts\TransactionContract;
use Costa\Shared\ValueObject\Input\InputNameObject;
use Costa\Shared\ValueObject\Input\InputValueObject;
use Costa\Shared\ValueObject\ModelObject;
use Costa\Shared\ValueObject\UuidObject;
use Throwable;

class UpdateUseCase
{
    public function __construct(
        protected ChargeRepositoryInterface $repo,
        protected RecurrenceRepositoryInterface $recurrence,
        protected TransactionContract $transaction,
        protected SupplierRepositoryInterface $relationship,
    ) {
        //
    }

    public function handle(DTO\Update\Input $input): DTO\Update\Output
    {
        /** @var ChargeEntity */
        $objEntity = $this->repo->find($input->id);
        $objSupplier = $this->relationship->find($input->supplier);

        if ($input->recurrence) {
            $input->recurrence = $this->recurrence->find((string) $input->recurrence)->id;
        }

        $objEntity->update(
            title: new InputNameObject($input->title),
            description: new InputNameObject($input->description, true),
            relationship: new ModelObject($objSupplier->id(), $objSupplier),
            value: new InputValueObject($input->value),
            date: $input->date,
            recurrence: $input->recurrence ? new UuidObject($input->recurrence) : null,
        );

        try {
            $this->repo->update($objEntity);
            $this->transaction->commit();

            return new DTO\Update\Output(
                id: $objEntity->id,
                title: $objEntity->title->value,
                description: $objEntity->description->value,
                value: $objEntity->value->value,
                customerId: $objSupplier->id(),
                recurrenceId: $objEntity->recurrence,
            );
        } catch (Throwable $e) {
            $this->transaction->rollback();
            throw $e;
        }
    }
}
