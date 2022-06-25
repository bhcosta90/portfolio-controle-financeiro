<?php

namespace App\Repository\Eloquent;

use App\Models\Relationship;
use App\Repository\Presenters\PaginatorPresenter;
use Core\Financial\Relationship\Modules\Company\Domain\CompanyEntity;
use Core\Financial\Relationship\Modules\Company\Repository\CompanyRepositoryInterface;
use Core\Shared\Abstracts\EntityAbstract;
use Core\Shared\Interfaces\PaginationInterface;

class CompanyEloquentRepository implements CompanyRepositoryInterface {
    public function __construct(
        protected Relationship $model,
    ) {
        //  
    }

    public function insert(EntityAbstract $entity): bool
    {
        return (bool) $this->model->create([
            'id' => $entity->id(),
            'entity' => get_class($entity),
            'name' => $entity->name->value,
            'document_type' => $entity->document?->type->value,
            'document_value' => $entity->document?->document,
        ]);
    }

    public function update(EntityAbstract $entity): bool
    {
        $obj = $this->findDb($entity->id);

        return $obj->update([
            'name' => $entity->name->value,
            'document_type' => $entity->document?->type->value,
            'document_value' => $entity->document?->document,
        ]);
    }

    public function find(string|int $key): EntityAbstract
    {
        return $this->entity($this->findDb($key));
    }

    public function findDb(string|int $key): object|array
    {
        return $this->model->find($key);
    }

    public function exist(string|int $key): bool
    {
        return $this->model->findDb($key)->count();
    }

    public function delete(EntityAbstract $entity): bool
    {
        return $this->findDb($entity->id)->delete();
    }

    public function paginate(?array $filter = null, ?int $page = 1, ?int $totalPage = 15): PaginationInterface
    {
        $result = $this->model
            ->where('entity', CompanyEntity::class)
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

    public function pluck(?array $filter = null): array
    {
        return $this->model->orderBy('name', 'asc')
            ->where('entity', CompanyEntity::class)
            ->pluck('name', 'id')->toArray();
    }

    public function entity(object $input): EntityAbstract
    {
        return CompanyEntity::create(
            name: $input->name,
            document_type: $input->document_type,
            document_value: $input->document_value,
            id: $input->id,
            createdAt: $input->created_at
        );
    }
}