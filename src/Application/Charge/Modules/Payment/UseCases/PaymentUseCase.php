<?php

namespace Core\Application\Charge\Modules\Payment\UseCases;

use Core\Application\BankAccount\Modules\Bank\Repository\BankRepository;
use Core\Application\Charge\Modules\Payment\Domain\PaymentEntity;
use Core\Application\Charge\Modules\Payment\Repository\PaymentRepository;
use Core\Application\Relationship\Modules\Company\Repository\CompanyRepository;
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
        private PaymentRepository $repository,
        private CompanyRepository $relationship,
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
        /** @var PaymentEntity */
        $objPayment = $this->repository->find($input->id);
        $objRelationship = $this->relationship->find($objPayment->company->id);
        
        if (empty($input->bank)) {
            $objTenant = $this->tenant->find($objPayment->tenant);
            $idAccount = $objTenant->account->id();
        } else {
            $objBank = $this->bank->find($input->bank);
            $idAccount = $objBank->accountEntity->id();
        }
        
        try {
            $objTransactionCustomer = TransactionEntity::create(
                $objPayment->tenant,
                $group = UuidObject::random(),
                $objPayment->title->value,
                $objRelationship->account->id(),
                $idAccount,
                $objPayment->id(),
                $objPayment,
                $objRelationship->id(),
                $objRelationship,
                $objRelationship->name->value,
                $input->value,
                TransactionTypeEnum::CREDIT->value,
                $input->chargeDateNext,
                null,
            );

            $objTransactionTenant = TransactionEntity::create(
                $objPayment->tenant,
                $group,
                $objPayment->title->value,
                $idAccount,
                $objRelationship->account->id(),
                $objPayment->id(),
                $objPayment,
                $objRelationship->id(),
                $objRelationship,
                $objRelationship->name->value,
                $input->value,
                TransactionTypeEnum::DEBIT->value,
                $input->date,
                null,
            );

            $rest = $objPayment->pay($input->value);
            if ($rest > 0 && $input->chargeNext && !empty($input->chargeDateNext)) {
                $objNewCharge = PaymentEntity::create(
                    tenant: $objPayment->tenant,
                    title: $objPayment->title->value,
                    resume: $objPayment->resume->value,
                    company: $objPayment->company->id,
                    recurrence: $objPayment->recurrence,
                    value: $objPayment->value->value,
                    pay: null,
                    group: $objPayment->group,
                    date: $input->date,
                    status: null,
                );
                $this->repository->insert($objNewCharge);
            }
            
            $retTransactionCustomer = $this->transaction->insert($objTransactionCustomer);
            $retTransactionTenant = $this->transaction->insert($objTransactionTenant);

            $this->repository->update($objPayment);
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
