<?php

namespace Costa\Modules\Charge\UseCases;

use Costa\Modules\Account\Entity\AccountEntity;
use Costa\Modules\Charge\Receive\Entity\ChargeEntity;
use Costa\Modules\Charge\Repository\ChargeRepositoryInterface;
use Costa\Shared\Contracts\TransactionContract;
use Costa\Shared\ValueObject\Input\InputNameObject;
use Costa\Shared\ValueObject\ModelObject;
use Throwable;

class CreateUseCase
{
    public function __construct(
        protected ChargeRepositoryInterface $repo,
        protected TransactionContract $transaction,
    ) {
        //
    }

    public function handle(DTO\Create\Input $input): DTO\Create\Output
    {
        $objEntity = new ChargeEntity(
            name: new InputNameObject($input->name),
        );

        try {
            $this->repo->insert($objEntity);
            $this->transaction->commit();

            return new DTO\Create\Output(
                id: $objEntity->id,
            );
        } catch (Throwable $e) {
            $this->transaction->rollback();
            throw $e;
        }
    }
}
