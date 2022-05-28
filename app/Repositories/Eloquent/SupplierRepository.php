<?php

namespace App\Repositories\Eloquent;

use App\Models\Relationship;
use App\Repositories\Presenters\PaginatorPresenter;
use Costa\Modules\Relationship\Supplier\Entity\SupplierEntity;
use Costa\Modules\Relationship\Supplier\Repository\SupplierRepositoryInterface;
use Costa\Shared\Abstracts\EntityAbstract;
use Costa\Shared\Contracts\PaginationInterface;
use Costa\Shared\ValueObject\DocumentObject;
use Costa\Shared\ValueObject\Enums\DocumentEnum;
use Costa\Shared\ValueObject\Input\InputNameObject;
use Costa\Shared\ValueObject\UuidObject;

class SupplierRepository implements SupplierRepositoryInterface
{
    public function __construct(
        protected Relationship $model,
    ) {
        //  
    }

    public function insert(EntityAbstract $entity): EntityAbstract
    {
        $obj = $this->model->create([
            'id' => $entity->id(),
            'entity' => get_class($entity),
            'name' => $entity->name->value,
            'document_type' => $entity->document?->type->value,
            'document_value' => $entity->document?->document,
        ]);

        return $this->entity($obj);
    }

    /** @param CustomerEntity $entity */
    public function update(EntityAbstract $entity): EntityAbstract
    {
        $obj = $this->findDb($entity->id);

        $obj->update([
            'name' => $entity->name->value,
            'document_type' => $entity->document?->type->value,
            'document_value' => $entity->document?->document,
        ]);

        return $this->entity($obj);
    }

    public function find(string|int $key): EntityAbstract
    {
        return $this->entity($this->findDb($key));
    }

    public function findDb(string|int $key): object|array
    {
        return $this->model->where('id', $key)->firstOrFail();
    }

    public function delete(EntityAbstract $entity): bool
    {
        return $this->findDb($entity->id)->delete();
    }

    public function paginate(?array $filter = null, ?int $page = 1, ?int $totalPage = 15): PaginationInterface
    {
        return new PaginatorPresenter($this->model->paginate());
    }

    public function all(?array $filter = null): array|object
    {
        return $this->model->get();
    }

    public function pluck(): array
    {
        return $this->model->pluck('name', 'id')->toArray();
    }

    protected function entity(object $entity)
    {
        return new SupplierEntity(
            id: new UuidObject($entity->id),
            name: new InputNameObject($entity->name),
            document: $entity->document_value
                ? new DocumentObject(DocumentEnum::from($entity->document_type), $entity->document_value)
                : null,
        );
    }
}
