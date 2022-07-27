<?php

namespace Core\Application\Transaction\UseCases;

use Core\Application\Charge\Modules\Payment\Domain\PaymentEntity;
use Core\Application\Charge\Modules\Payment\Repository\PaymentRepository;
use Core\Application\Charge\Modules\Receive\Domain\ReceiveEntity;
use Core\Application\Charge\Modules\Receive\Repository\ReceiveRepository;
use Core\Application\Charge\Shared\Contracts\ChargePayInterface;
use Core\Application\Transaction\Domain\TransactionEntity as Entity;
use Core\Application\Transaction\Exceptions\TransactionException;
use Core\Application\Transaction\Repository\TransactionRepository as Repo;
use Core\Application\Transaction\Shared\Enums\TransactionStatusEnum;
use Core\Shared\Abstracts\EntityAbstract;
use Core\Shared\Interfaces\EventManagerInterface;
use Core\Shared\Interfaces\TransactionInterface;
use Core\Shared\UseCases\Delete\{DeleteInput, DeleteOutput};
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
        /** @var Entity */
        $entity = $this->repository->find($input->id);
        if ($entity->status == TransactionStatusEnum::COMPLETE) {
            throw new TransactionException('This transaction cannot be canceled');
        }

        try {
            
            match ($entity->entity->type) {
                ReceiveEntity::class => call_user_func(function() use($entity){
                    /** @var ChargePayInterface|EntityAbstract */
                    $obj = $this->receiveRepository->find($entity->entity->id);
                    $obj->cancel($entity->value->value);
                    $this->receiveRepository->update($obj);
                    return $obj;
                }),
                PaymentEntity::class => call_user_func(function() use($entity){
                    /** @var ChargePayInterface|EntityAbstract */
                    $obj = $this->receiveRepository->find($entity->entity->id);
                    $obj->cancel($entity->value->value);
                    $this->receiveRepository->update($obj);
                    return $obj;
                }),
                default => throw new Exception('Error'),
            };

            $ret = new DeleteOutput($this->repository->delete($entity));

            $this->transactionInterface->commit();
            return $ret ?? new DeleteOutput(false);
        } catch (Throwable $e) {
            $this->transactionInterface->rollback();
            throw $e;
        }
    }
}
