<?php

namespace Costa\Modules\Account\Repository;

use Costa\Shareds\Abstracts\EntityAbstract;
use Costa\Modules\Account\Entities\AccountEntity as Entity;
use Costa\Shareds\ValueObjects\ModelObject;

interface AccountRepositoryInterface
{
    public function insert(EntityAbstract $entity): Entity;

    public function find(ModelObject $model): Entity;

    public function update(EntityAbstract $entity): Entity;

    public function addValue(EntityAbstract $entity, float $value): Entity;

    public function subValue(EntityAbstract $entity, float $value): Entity;
}
