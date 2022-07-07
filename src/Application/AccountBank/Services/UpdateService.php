<?php

namespace Core\Application\AccountBank\Services;

use Core\Application\AccountBank\Domain\AccountBankEntity as Entity;
use Core\Application\AccountBank\Repository\AccountBankRepository as Repo;
use Core\Application\Charge\Shared\Enums\ChargeStatusEnum;
use Core\Application\Payment\Domain\PaymentEntity;
use Core\Application\Payment\Repository\PaymentRepository;
use Core\Application\Payment\Shared\Enums\PaymentTypeEnum;

class UpdateService
{
    public function __construct(
        private Repo              $repository,
        private PaymentRepository $payment
    )
    {
        //
    }

    public function handle(DTO\Update\Input $input): DTO\Update\Output
    {
        /** @var Entity */
        $entity = $this->repository->find($input->id);
        $valueOld = $entity->value;

        $type = $valueOld > $input->value ? PaymentTypeEnum::CREDIT : PaymentTypeEnum::DEBIT;
        $entity->update($input->name, $input->value);
        $this->repository->update($entity);

        if ($valueOld !== $input->value) {
            $objPayment = PaymentEntity::create(
                tenant: $entity->tenant,
                relationship: null,
                charge: null,
                title: $valueOld > $input->value ? "Saque" : "Depósito",
                resume: null,
                name: null,
                bank: $input->id,
                value: abs($valueOld - $input->value),
                status: ChargeStatusEnum::COMPLETED->value,
                type: $type->value,
                date: null,
            );
            $objPayment->bankValue($valueOld);
            $this->payment->insert($objPayment);
        }
        return new DTO\Update\Output(
            name: $entity->name->value,
            value: $entity->value,
            id: $entity->id(),
        );
    }
}
