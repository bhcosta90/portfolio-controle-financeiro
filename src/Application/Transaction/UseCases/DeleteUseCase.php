<?php

namespace Core\Application\Transaction\UseCases;

use Core\Application\Charge\Modules\Payment\Domain\PaymentEntity;
use Core\Application\Charge\Modules\Payment\Repository\PaymentRepository;
use Core\Application\Charge\Modules\Receive\Domain\ReceiveEntity;
use Core\Application\Charge\Modules\Receive\Repository\ReceiveRepository;
use Core\Application\Charge\Shared\Contracts\ChargePayInterface;
use Core\Application\Transaction\Domain\TransactionEntity as Entity;
use Core\Application\Transaction\Domain\TransactionEntity;
use Core\Application\Transaction\Exceptions\TransactionException;
use Core\Application\Transaction\Repository\TransactionRepository as Repo;
use Core\Application\Transaction\Shared\Enums\TransactionStatusEnum;
use Core\Application\Transaction\Shared\Enums\TransactionTypeEnum;
use Core\Shared\Abstracts\EntityAbstract;
use Core\Shared\Interfaces\EventManagerInterface;
use Core\Shared\Interfaces\TransactionInterface;
use Core\Shared\UseCases\Delete\{DeleteInput, DeleteOutput};
use Core\Shared\ValueObjects\UuidObject;
use Exception;
use Throwable;

class DeleteUseCase
{
    public function __construct(
        private Repo $repository,
        private ReceiveRepository $receiveRepository,
        private PaymentRepository $paymentRepository,
        private TransactionInterface $transactionInterface,
        private EventManagerInterface $eventManagerInterface,
    ) {
        //
    }

    public function handle(DeleteInput $input): DeleteOutput
    {
        try {
            /** @var Entity */
            $entity = $this->repository->find($input->id);
            if ($entity->status == TransactionStatusEnum::PENDING) {
                /** @var ChargePayInterface|EntityAbstract */
                $objCharge = match ($entity->entity->type) {
                    ReceiveEntity::class => $this->receiveRepository->find($entity->entity->id),
                    PaymentEntity::class => $this->paymentRepository->find($entity->entity->id),
                    default => throw new Exception('Error'),
                };

                $objCharge->cancel($entity->value->value);

                match ($entity->entity->type) {
                    ReceiveEntity::class => $this->receiveRepository->update($objCharge),
                    PaymentEntity::class => $this->paymentRepository->update($objCharge),
                    default => throw new Exception('Error'),
                };

                $ret = new DeleteOutput($this->repository->delete($entity));
            } else {
                throw new TransactionException('This transaction cannot be canceled');
            }

            $this->transactionInterface->commit();
            return $ret ?? new DeleteOutput(false);
        } catch (Throwable $e) {
            $this->transactionInterface->rollback();
            throw $e;
        }
    }

    private function reverseReceive(TransactionEntity $transaction)
    {
        $ret = [];
        $id = UuidObject::random();

        $ret[] = $new = TransactionEntity::create(
            tenant: $transaction->tenant,
            group: $id,
            title: $transaction->title,
            accountTo: $transaction->accountTo,
            accountFrom: $transaction->accountFrom,
            transaction_id: $transaction->entity->id,
            transaction_type: $transaction->entity->type,
            relationship_id: $transaction->relationship->id,
            relationship_type: $transaction->relationship->type,
            relationship_name: $transaction->relationship->value,
            value: $transaction->value->value,
            type: TransactionTypeEnum::DEBIT->value,
            date_execute: null,
        );

        $this->repository->insert($new);

        $ret[] = $new = TransactionEntity::create(
            tenant: $transaction->tenant,
            group: $id,
            title: $transaction->title,
            accountTo: $transaction->accountFrom,
            accountFrom: $transaction->accountTo,
            transaction_id: $transaction->entity->id,
            transaction_type: $transaction->entity->type,
            relationship_id: $transaction->relationship->id,
            relationship_type: $transaction->relationship->type,
            relationship_name: $transaction->relationship->value,
            value: $transaction->value->value,
            type: TransactionTypeEnum::CREDIT->value,
            date_execute: null,
        );

        $this->repository->insert($new);

        return $ret;
    }

    private function reversePayment(TransactionEntity $transaction)
    {
        $ret = [];
        $id = UuidObject::random();

        $ret[] = $new = TransactionEntity::create(
            tenant: $transaction->tenant,
            group: $id,
            title: $transaction->title,
            accountTo: $transaction->accountTo,
            accountFrom: $transaction->accountFrom,
            transaction_id: $transaction->entity->id,
            transaction_type: $transaction->entity->type,
            relationship_id: $transaction->relationship->id,
            relationship_type: $transaction->relationship->type,
            relationship_name: $transaction->relationship->value,
            value: $transaction->value->value,
            type: TransactionTypeEnum::CREDIT->value,
            date_execute: null,
        );

        $this->repository->insert($new);

        $ret[] = $new = TransactionEntity::create(
            tenant: $transaction->tenant,
            group: $id,
            title: $transaction->title,
            accountTo: $transaction->accountFrom,
            accountFrom: $transaction->accountTo,
            transaction_id: $transaction->entity->id,
            transaction_type: $transaction->entity->type,
            relationship_id: $transaction->relationship->id,
            relationship_type: $transaction->relationship->type,
            relationship_name: $transaction->relationship->value,
            value: $transaction->value->value,
            type: TransactionTypeEnum::DEBIT->value,
            date_execute: null,
        );

        $this->repository->insert($new);

        return $ret;
    }
}
