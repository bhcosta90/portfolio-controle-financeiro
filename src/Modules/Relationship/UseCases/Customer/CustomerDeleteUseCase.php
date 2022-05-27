<?php

namespace Costa\Modules\Relationship\UseCases\Customer;

use Costa\Modules\Relationship\Repository\CustomerRepositoryInterface;
use Costa\Shareds\ValueObjects\DeleteObject;

class CustomerDeleteUseCase
{
    public function __construct(private CustomerRepositoryInterface $repo)
    {
        //
    }

    public function exec(DTO\Find\Input $input): DeleteObject
    {
        return new DeleteObject(success: $this->repo->delete($input->id));
    }
}
