<?php

namespace Costa\Modules\Charge\UseCases;

use Costa\Modules\Account\Entity\AccountEntity;
use Costa\Modules\Account\Repository\AccountRepositoryInterface;
use Costa\Modules\Charge\Receive\Entity\ChargeEntity;
use Costa\Modules\Charge\Repository\ChargeRepositoryInterface;
use Costa\Shared\Contracts\TransactionContract;
use Costa\Shared\ValueObject\Input\InputNameObject;
use Costa\Shared\ValueObject\ModelObject;
use Throwable;

class UpdateUseCase
{
    public function __construct(
        protected ChargeRepositoryInterface $repo,
        protected TransactionContract $transaction,
    ) {
        //
    }

    public function handle(DTO\Update\Input $input): DTO\Update\Output
    {
        /** @var ChargeEntity */
        $objEntity = $this->repo->find($input->id);
        $objEntity->update(
            name: new InputNameObject($input->name),
        );

        try {
            $this->repo->update($objEntity);
            $this->transaction->commit();

            return new DTO\Update\Output(
                id: $objEntity->id,
            );
        } catch (Throwable $e) {
            $this->transaction->rollback();
            throw $e;
        }
    }
}
