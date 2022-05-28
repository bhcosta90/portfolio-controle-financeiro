<?php

namespace Costa\Modules\Account\Repository;

use Costa\Modules\Account\Entity\AccountEntity;
use Costa\Shared\Contracts\RepositoryInterface;
use Costa\Shared\ValueObject\ModelObject;

interface AccountRepositoryInterface extends RepositoryInterface
{
    public function findByEntity(ModelObject $entity): AccountEntity;
}
