<?php

namespace Costa\Modules\Charge\Payment\UseCases;

use Costa\Modules\Charge\Payment\Entity\ChargeEntity;
use Costa\Modules\Charge\Payment\Repository\ChargeRepositoryInterface;
use Costa\Shared\ValueObject\DeleteObject;

class DeleteUseCase
{
    public function __construct(
        protected ChargeRepositoryInterface $repo
    ) {
        //
    }

    public function handle(DTO\Find\Input $input): DeleteObject
    {
        /** @var ChargeEntity */
        $objEntity = $this->repo->find($input->id);
        return new DeleteObject($this->repo->delete($objEntity));
    }
}
