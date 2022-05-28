<?php

namespace Costa\Modules\Charge\Receive\UseCases;

use Costa\Modules\Charge\Receive\Entity\ChargeEntity;
use Costa\Modules\Charge\Receive\Repository\ChargeRepositoryInterface;

class FindUseCase
{
    public function __construct(
        protected ChargeRepositoryInterface $repo
    ) {
        //
    }

    public function handle(DTO\Find\Input $input): DTO\Find\Output
    {
        /** @var ChargeEntity */
        $objEntity = $this->repo->find($input->id);

        return new DTO\Find\Output(
            id: $objEntity->id,
            name: $objEntity->name->value,
            value: $objEntity->days->value,
        );
    }
}
