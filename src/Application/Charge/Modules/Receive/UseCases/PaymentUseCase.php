<?php

namespace Core\Application\Charge\Modules\Receive\UseCases;

use Core\Application\BankAccount\Modules\Bank\Repository\BankRepository;
use Core\Application\Charge\Modules\Receive\Domain\ReceiveEntity;
use Core\Application\Charge\Modules\Receive\Repository\ReceiveRepository;
use Core\Application\Relationship\Modules\Customer\Repository\CustomerRepository;
use Core\Application\Tenant\Repository\TenantRepository;
use Core\Application\Transaction\Domain\TransactionEntity;
use Core\Application\Transaction\Repository\TransactionRepository;
use Core\Application\Transaction\Shared\Enums\TransactionTypeEnum;
use Core\Shared\Interfaces\EventManagerInterface;
use Core\Shared\Interfaces\TransactionInterface;
use Core\Shared\ValueObjects\UuidObject;
use Throwable;

class PaymentUseCase
{
    public function __construct(
        private ReceiveRepository $repository,
        private CustomerRepository $relationship,
        private TenantRepository $tenant,
        private TransactionRepository $transaction,
        private EventManagerInterface $event,
        private TransactionInterface $transactionInterface,
        private BankRepository $bank,
    ) {
        //
    }

    public function handle(DTO\Payment\Input $input): DTO\Payment\Output
    {
        /** @var ReceiveEntity */
        $objReceive = $this->repository->find($input->id);
        $objRelationship = $this->relationship->find($objReceive->customer->id);
        
        if (empty($input->bank)) {
            $objTenant = $this->tenant->find($objReceive->tenant);
            $idAccount = $objTenant->account->id();
        } else {
            $objBank = $this->bank->find($input->bank);
            $idAccount = $objBank->accountEntity->id();
        }

        try {
            $objTransactionCustomer = TransactionEntity::create(
                $objReceive->tenant,
                $group = UuidObject::random(),
                $objReceive->title->value,
                $objRelationship->account->id(),
                $idAccount,
                $objReceive->id(),
                $objReceive,
                $objRelationship->id(),
                $objRelationship,
                $objRelationship->name->value,
                $input->value,
                TransactionTypeEnum::DEBIT->value,
                $input->date,
                null,
            );

            $objTransactionTenant = TransactionEntity::create(
                $objReceive->tenant,
                $group,
                $objReceive->title->value,
                $idAccount,
                $objRelationship->account->id(),
                $objReceive->id(),
                $objReceive,
                $objRelationship->id(),
                $objRelationship,
                $objRelationship->name->value,
                $input->value,
                TransactionTypeEnum::CREDIT->value,
                $input->date,
                null,
            );

            $rest = $objReceive->pay($input->value);
            if ($rest > 0 && $input->chargeNext && !empty($input->chargeDateNext)) {
                $objNewCharge = ReceiveEntity::create(
                    tenant: $objReceive->tenant,
                    title: $objReceive->title->value,
                    resume: $objReceive->resume->value,
                    customer: $objReceive->customer->id,
                    recurrence: $objReceive->recurrence,
                    value: $objReceive->value->value,
                    pay: null,
                    group: $objReceive->group,
                    date: $input->chargeDateNext,
                    status: null,
                );
                $this->repository->insert($objNewCharge);
            }

            $retTransactionCustomer = $this->transaction->insert($objTransactionCustomer);
            $retTransactionTenant = $this->transaction->insert($objTransactionTenant);

            $this->repository->update($objReceive);
            $this->event->dispatch(array_merge($objTransactionCustomer->events, $objTransactionTenant->events));
            $this->transactionInterface->commit();
            return new DTO\Payment\Output(
                $retTransactionCustomer && $retTransactionTenant,
                !empty($objNewCharge) ? $objNewCharge->id() : null
            );
        } catch (Throwable $e) {
            $this->transactionInterface->rollback();
            throw $e;
        }
    }
}
