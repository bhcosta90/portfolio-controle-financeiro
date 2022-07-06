<?php

namespace Core\Application\Charge\Modules\Payment\Services;

use Core\Application\AccountBank\Domain\AccountBankEntity;
use Core\Application\AccountBank\Repository\AccountBankRepository;
use Core\Application\Charge\Modules\Payment\Domain\PaymentEntity as Entity;
use Core\Application\Charge\Modules\Payment\Repository\ChargePaymentRepository as Repo;
use Core\Application\Charge\Modules\Recurrence\Domain\RecurrenceEntity;
use Core\Application\Charge\Modules\Recurrence\Repository\RecurrenceRepository;
use Core\Application\Charge\Shared\Enums\ChargeStatusEnum;
use Core\Application\Charge\Shared\Exceptions\ChargeException;
use Core\Application\Payment\Domain\PaymentEntity;
use Core\Application\Payment\Repository\PaymentRepository;
use Core\Application\Payment\Shared\Enums\PaymentTypeEnum;
use Core\Application\Relationship\Modules\Company\Repository\CompanyRepository as RelationshipRepository;
use Core\Shared\Interfaces\TransactionInterface;
use Core\Shared\ValueObjects\EntityObject;
use Exception;
use Throwable;

class PaymentService
{
    public function __construct(
        private Repo $repository,
        private TransactionInterface $transaction,
        private RelationshipRepository $relationship,
        private RecurrenceRepository $recurrence,
        private AccountBankRepository $bank,
        private PaymentRepository $payment,
    ) {
        //
    }

    public function handle(DTO\Payment\Input $input): DTO\Payment\Output
    {
        /** @var Entity */
        if (!$objCharge = $this->repository->find($input->id)) {
            throw new ChargeException('Payment not found');
        }

        /** @var RecurrenceEntity */
        if ($objCharge->recurrence && !$objRecurrence = $this->recurrence->find($objCharge->recurrence)) {
            throw new Exception('Recurrence not found');
        }

        /** @var AccountBankEntity */
        if ($input->idAccountBank && !$this->bank->find($input->idAccountBank)) {
            throw new Exception('Bank account not found');
        }

        $objRelationship = $this->relationship->find($objCharge->company->id);

        $objCharge->pay($input->valuePayment, $input->valueCharge);

        try {
            $this->repository->update($objCharge);

            $rest = 0;
            if ($input->valueCharge !== $objCharge->value->value) {
                $rest = $objCharge->value->value - $objCharge->pay->value;
            }

            if ($rest > 0) {
                $objRecurrenceRest = $objRecurrence ?? RecurrenceEntity::create('teste', 30);
                $objChargeNew = Entity::create(
                    tenant: $objCharge->tenant,
                    title: $objCharge->title->value,
                    resume: $objCharge->resume->value,
                    company: $objCharge->company->id,
                    recurrence: null,
                    value: $rest,
                    pay: null,
                    group: $objCharge->group,
                    date: $objRecurrenceRest->calculate($objCharge->date->format('Y-m-d'))->format('Y-m-d'),
                );
                $this->repository->insert($objChargeNew);
            }

            if ($objCharge->recurrence) {
                $objChargeNew = Entity::create(
                    tenant: $objCharge->tenant,
                    title: $objCharge->title->value,
                    resume: $objCharge->resume->value,
                    company: $objCharge->company->id,
                    recurrence: $objCharge->recurrence,
                    value: $objCharge->value->value,
                    pay: null,
                    group: $objCharge->group,
                    date: $objRecurrence->calculate($objCharge->date->format('Y-m-d'))->format('Y-m-d'),
                );
                $this->repository->insert($objChargeNew);
                $newCharge = $objChargeNew->id();
            }
            $objPayment = PaymentEntity::create(
                relationship: $objCharge->company,
                bank: $input->idAccountBank,
                value: $input->valuePayment,
                status: null,
                type: PaymentTypeEnum::DEBIT->value,
                date: $input->date,
                charge: new EntityObject($input->id, get_class($objCharge)),
                title: $objCharge->title->value,
                resume: $objCharge->resume->value,
                name: $objRelationship->name->value,
            );
            $this->payment->insert($objPayment);
            $this->transaction->commit();
            return new DTO\Payment\Output(idPayment: $input->id, idCharge: $newCharge ?? null);
        } catch (Throwable $e) {
            $this->transaction->rollback();
            throw $e;
        }
    }
}
