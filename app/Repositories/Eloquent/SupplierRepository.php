<?php

namespace App\Repositories\Eloquent;

use App\Models\Relationship;
use Costa\Modules\Relationship\SupplierEntity;
use Costa\Shared\ValueObject\Input\InputNameObject;
use Costa\Shared\ValueObject\UuidObject;

class SupplierRepository implements CustomerRepository
{
    public function __construct(
        private Relationship $model,
    ) {
        //  
    }

    protected function entity(object $entity)
    {
        return new SupplierEntity(
            id: new UuidObject($entity->id),
            name: new InputNameObject($entity->name),
            document: null,
        );
    }

    protected function getEntity()
    {
        return SupplierEntity::class;
    }
}
