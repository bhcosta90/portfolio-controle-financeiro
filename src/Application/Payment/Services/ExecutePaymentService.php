<?php

namespace Core\Application\Payment\Services;

use Core\Application\AccountBank\Domain\AccountBankEntity;
use Core\Application\AccountBank\Repository\AccountBankRepository;
use Core\Application\Payment\Domain\PaymentEntity;
use Core\Application\Payment\Repository\PaymentRepository;
use Core\Application\Payment\Shared\Enums\PaymentStatusEnum;
use Core\Application\Payment\Shared\Enums\PaymentTypeEnum;
use Core\Application\Relationship\Modules\Company\Domain\CompanyEntity;
use Core\Application\Relationship\Modules\Company\Repository\CompanyRepository;
use Core\Application\Relationship\Modules\Customer\Domain\CustomerEntity;
use Core\Application\Relationship\Modules\Customer\Repository\CustomerRepository;
use Core\Shared\Abstracts\EntityAbstract;
use Core\Shared\Contracts\ValueInterface;
use Core\Shared\Interfaces\TransactionInterface;
use Exception;

class ExecutePaymentService
{
    public function __construct(
        private PaymentRepository     $repository,
        private TransactionInterface  $transaction,
        private CustomerRepository    $customer,
        private CompanyRepository     $company,
        private AccountBankRepository $account,
    )
    {
        //
    }

    public function handle(DTO\ExecutePayment\Input $input): DTO\ExecutePayment\Output
    {
        $ret = [];
        $this->repository->updateStatus(
            $input->date->format('Y-m-d H:i:s'),
            PaymentStatusEnum::PENDING->value,
            PaymentStatusEnum::PROCESSING->value
        );

        $limit = 100;
        do {
            $data = $this->repository->getListStatus(PaymentStatusEnum::PROCESSING->value, $limit)->items();
            try {
                foreach ($data as $rs) {
                    $ret[] = $this->executePayment($this->repository->entity($rs));
                }
                $this->transaction->commit();
            } catch (Exception $e) {
                $this->transaction->rollback();
                throw $e;
            }
        } while (count($data) === $limit);

        return new DTO\ExecutePayment\Output($ret);
    }

    private function executePayment(PaymentEntity $objPayment)
    {
        match ($objPayment->relationship->type) {
            CustomerEntity::class => $objRelationship = $this->customer->find($objPayment->relationship->id),
            CompanyEntity::class => $objRelationship = $this->company->find($objPayment->relationship->id),
            default => throw new Exception('Relationship ' . $objPayment->relationship->type . ' do not passed'),
        };

        /** @var AccountBankEntity $objBank */
        $objBank = $objPayment->bank ? $this->account->find($objPayment->bank) : null;

        if ($objPayment->type == PaymentTypeEnum::DEBIT) {
            /** @var ValueInterface|EntityAbstract $objRelationship */
            $objRelationship->addValue($objPayment->value->value, $objPayment->id());

            if ($objBank) {
                $objPayment->bankValue($objBank->value);
                $objBank->removeValue($objPayment->value->value, $objPayment->id());
            }
        } else {
            /** @var ValueInterface|EntityAbstract $objRelationship */
            $objRelationship->removeValue($objPayment->value->value, $objPayment->id());

            if ($objBank) {
                $objPayment->bankValue($objBank->value);
                $objBank->addValue($objPayment->value->value, $objPayment->id());
            }
        }

        match ($objPayment->relationship->type) {
            CustomerEntity::class => $this->customer->update($objRelationship),
            CompanyEntity::class => $this->company->update($objRelationship),
            default => throw new Exception('Relationship ' . $objPayment->relationship->type . ' do not passed'),
        };

        if ($objBank) {
            $this->account->update($objBank);
        }
        $objPayment->complete();
        $this->repository->update($objPayment);
        return $objPayment->id();
    }
}
