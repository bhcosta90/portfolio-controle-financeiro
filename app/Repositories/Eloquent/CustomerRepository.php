<?php

namespace App\Repositories\Eloquent;

use App\Models\Relationship;
use App\Repositories\Presenters\PaginatorPresenter;
use Costa\Modules\Relationship\Customer\Entity\CustomerEntity;
use Costa\Modules\Relationship\Customer\Repository\CustomerRepositoryInterface;
use Costa\Shared\Abstracts\EntityAbstract;
use Costa\Shared\Contracts\PaginationInterface;
use Costa\Shared\ValueObject\DocumentObject;
use Costa\Shared\ValueObject\Enums\DocumentEnum;
use Costa\Shared\ValueObject\Input\InputNameObject;
use Costa\Shared\ValueObject\UuidObject;

class CustomerRepository implements CustomerRepositoryInterface
{
    public function __construct(
        protected Relationship $model,
    ) {
        //  
    }

    protected function entity(object $entity)
    {
        return new CustomerEntity(
            id: new UuidObject($entity->id),
            name: new InputNameObject($entity->name),
            document: $entity->document_value
                ? new DocumentObject(DocumentEnum::from($entity->document_type), $entity->document_value)
                : null,
        );
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
        return $this->model->findOrFail($key);
    }

    public function delete(EntityAbstract $entity): bool
    {
        return $this->findDb($entity->id)->delete();
    }

    public function paginate(?array $filter = null, ?int $page = 1, ?int $totalPage = 15): PaginationInterface
    {
        $result = $this->model
            ->where('entity', CustomerEntity::class)
            ->orderBy('name', 'asc');
            
        return new PaginatorPresenter($result->paginate(
            page: $page,
            perPage: $totalPage,
        ));
    }

    public function all(?array $filter = null): array|object
    {
        return $this->model->get();
    }

    public function pluck(): array
    {
        return $this->model->orderBy('name', 'asc')->pluck('name', 'id')->toArray();
    }
}
