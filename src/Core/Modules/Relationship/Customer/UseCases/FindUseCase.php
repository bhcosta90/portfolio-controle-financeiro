<?php

namespace Costa\Modules\Relationship\Customer\UseCases;

use Costa\Modules\Relationship\Customer\Entity\CustomerEntity;
use Costa\Modules\Relationship\Customer\Repository\CustomerRepositoryInterface;

class FindUseCase
{
    public function __construct(
        protected CustomerRepositoryInterface $repo
    ) {
        //
    }

    public function handle(DTO\Find\Input $input): DTO\Find\Output
    {
        /** @var CustomerEntity */
        $objEntity = $this->repo->find($input->id);
        return new DTO\Find\Output(
            id: $objEntity->id,
            name: $objEntity->name->value,
            document_type: $objEntity->document?->type->value,
            document_value: $objEntity->document?->document,
        );
    }
}
