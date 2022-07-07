<?php

namespace Core\Application\Payment\Services;

use Core\Application\Charge\Modules\Payment\Domain\PaymentEntity;
use Core\Application\Charge\Modules\Payment\Repository\ChargePaymentRepository;
use Core\Application\Charge\Modules\Receive\Domain\ReceiveEntity;
use Core\Application\Charge\Modules\Receive\Repository\ChargeReceiveRepository;
use Core\Application\Payment\Domain\PaymentEntity as Entity;
use Core\Application\Payment\Exceptions\PaymentException;
use Core\Application\Payment\Repository\PaymentRepository as Repo;
use Core\Application\Payment\Shared\Enums\PaymentStatusEnum;
use Core\Shared\Interfaces\TransactionInterface;
use Core\Shared\UseCases\Delete\{DeleteInput, DeleteOutput};
use Throwable;

class DeleteService
{
    public function __construct(
        private Repo                    $repository,
        private ChargeReceiveRepository $receive,
        private ChargePaymentRepository $payment,
        private TransactionInterface    $transaction
    )
    {
        //
    }

    public function handle(DeleteInput $input): DeleteOutput
    {
        /** @var Entity */
        $entity = $this->repository->find($input->id);
        if ($entity->status === PaymentStatusEnum::PENDING) {
            try {
                if ($entity->charge->type) {
                    $objCharge = match ($entity->charge->type) {
                        ReceiveEntity::class => $this->receive->find($entity->charge->id),
                        PaymentEntity::class => $this->payment->find($entity->charge->id),
                        default => throw new PaymentException('Type ' . $entity->charge->type . ' do not implemented'),
                    };

                    /** @var ReceiveEntity|PaymentEntity $objCharge */
                    $objCharge->cancel($entity->value->value);
                    match ($entity->charge->type) {
                        ReceiveEntity::class => $this->receive->update($objCharge),
                        PaymentEntity::class => $this->payment->update($objCharge),
                        default => throw new PaymentException('Type ' . $entity->charge->type . ' do not implemented'),
                    };
                }
                $ret = $this->repository->delete($entity);
                $this->transaction->commit();
                return new DeleteOutput($ret);
            } catch (Throwable $e) {
                $this->transaction->rollback();
                throw $e;
            }
        }

        throw new PaymentException('This payment has already been processed');
    }
}
