<?php

namespace Costa\Modules\Charge\Receive\UseCases;

use Costa\Modules\Account\Entity\AccountEntity;
use Costa\Modules\Account\Repository\AccountRepositoryInterface;
use Costa\Modules\Charge\Receive\Entity\ChargeEntity;
use Costa\Modules\Charge\Receive\Repository\ChargeRepositoryInterface;
use Costa\Modules\Relationship\Customer\Repository\CustomerRepositoryInterface;
use Costa\Shared\Contracts\TransactionContract;
use Costa\Shared\ValueObject\Input\InputNameObject;
use Costa\Shared\ValueObject\Input\InputValueObject;
use Costa\Shared\ValueObject\ModelObject;
use Costa\Shared\ValueObject\UuidObject;
use DateTime;
use Throwable;

class UpdateUseCase
{
    public function __construct(
        protected ChargeRepositoryInterface $repo,
        protected TransactionContract $transaction,
        protected CustomerRepositoryInterface $relationship,
    ) {
        //
    }

    public function handle(DTO\Update\Input $input): DTO\Update\Output
    {
        /** @var ChargeEntity */
        $objEntity = $this->repo->find($input->id);
        $objCustomer = $this->relationship->find($input->customer);

        $objEntity->update(
            title: new InputNameObject($input->title),
            description: new InputNameObject($input->description, true),
            relationship: new ModelObject($objCustomer->id(), $objCustomer),
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
                customerId: $objCustomer->id(),
            );
        } catch (Throwable $e) {
            $this->transaction->rollback();
            throw $e;
        }
    }
}
