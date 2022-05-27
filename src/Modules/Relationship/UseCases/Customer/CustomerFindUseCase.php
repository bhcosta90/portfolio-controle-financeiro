<?php

namespace Costa\Modules\Relationship\UseCases\Customer;

use Costa\Modules\Relationship\Repository\CustomerRepositoryInterface;

class CustomerFindUseCase
{
    public function __construct(
        private CustomerRepositoryInterface $repo,
    ) {
        //
    }

    public function exec(DTO\Find\Input $input): DTO\Find\Output
    {
        $obj = $this->repo->find($input->id);

        return new DTO\Find\Output(
            name: $obj->name->value,
            id: $obj->id(),
        );
    }
}
