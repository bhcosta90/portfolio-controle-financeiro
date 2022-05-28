<?php

namespace Costa\Modules\Relationship\Customer\UseCases;

use Costa\Modules\Relationship\Customer\Entity\CustomerEntity;
use Costa\Modules\Relationship\Customer\Repository\CustomerRepositoryInterface;
use Costa\Shared\ValueObject\DeleteObject;

class DeleteUseCase
{
    public function __construct(
        protected CustomerRepositoryInterface $relationship
    ) {
        //
    }

    public function handle(DTO\Find\Input $input): DeleteObject
    {
        /** @var CustomerEntity */
        $objEntity = $this->relationship->find($input->id);
        return new DeleteObject($this->relationship->delete($objEntity));
    }
}
