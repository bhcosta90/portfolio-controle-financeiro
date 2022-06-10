<?php

namespace Costa\Modules\Payment\UseCases;

use App\Repositories\Eloquent\ChargePaymentRepository;
use App\Repositories\Eloquent\ChargeReceiveRepository;
use Costa\Modules\Account\Repository\AccountRepositoryInterface;
use Costa\Modules\Charge\Abstracts\ChargeAbstract;
use Costa\Modules\Charge\Payment\Entity\ChargeEntity;
use Costa\Modules\Payment\Repository\PaymentRepositoryInterface;
use Costa\Shared\Contracts\TransactionContract;
use Costa\Shared\ValueObject\DeleteObject;
use Exception;

class DeleteUseCase
{
    public function __construct(
        protected PaymentRepositoryInterface $repo,
        protected TransactionContract $transaction,
        protected AccountRepositoryInterface $account,
        protected ChargeReceiveRepository $chargeReceiveRepository,
        protected ChargePaymentRepository $chargePaymentRepository,
    ) {
        //
    }

    public function handle(DTO\Find\Input $input): DeleteObject
    {
        try {
            /** @var ChargeEntity */
            $objEntity = $this->repo->find($input->id);

            if ($objEntity->accountFrom) {
                $this->account->incrementValue($objEntity->accountFrom, $objEntity->value);
            }

            if ($objEntity->accountTo) {
                $this->account->decrementValue($objEntity->accountTo, $objEntity->value);
            }

            /** @var ChargeAbstract */
            $objCharge = match($objEntity->charge->type) {
                \Costa\Modules\Charge\Receive\Entity\ChargeEntity::class => $this->chargeReceiveRepository->find(
                    $objEntity->charge->id
                ),
                \Costa\Modules\Charge\Payment\Entity\ChargeEntity::class => $this->chargePaymentRepository->find(
                    $objEntity->charge->id
                ),
                default => throw new Exception('Type ' . $objEntity->charge->type .' not configured'),
            };

            $objCharge->payCancel($objEntity->value);
            
            /** @var ChargeAbstract */
            $objCharge = match($objEntity->charge->type) {
                \Costa\Modules\Charge\Receive\Entity\ChargeEntity::class => $this->chargeReceiveRepository->update(
                    $objCharge),
                \Costa\Modules\Charge\Payment\Entity\ChargeEntity::class => $this->chargePaymentRepository->update(
                    $objCharge
                ),
                default => throw new Exception('Type ' . $objEntity->charge->type .' not configured'),
            };
            
            $this->transaction->commit();
            return new DeleteObject($this->repo->delete($objEntity));
        } catch (Exception $e) {
            $this->transaction->rollback();
            throw $e;
        }
    }
}
