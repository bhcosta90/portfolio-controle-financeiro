<?php

namespace App\Repositories\Eloquent;

use App\Models\Account;
use App\Models\Relationship;
use Costa\Modules\Account\Entities\AccountEntity;
use Costa\Modules\Relationship\Repository\RelationshipRepositoryInterface;
use Costa\Shareds\ValueObjects\ModelObject;

class RelationshipRepository implements RelationshipRepositoryInterface
{
    public function __construct(private Relationship $model, private Account $account)
    {
        //
    }

    public function verify(int|string $id): bool
    {
        return $this->model->where('uuid', $id)->count();
    }
    
    public function name(int|string $id): string
    {
        return $this->model->where('uuid', $id)->firstOrFail()->name;
    }

    public function pluck(array $filter = []): array
    {
        return $this->model->whereIn('model', $filter)->orderBy('name')->pluck('name', 'uuid')->toArray();
    }

    public function getAccount(int|string $id): AccountEntity
    {
        $obj = $this->account->where('model_id', $id)->first();  
        return new AccountEntity(new ModelObject($obj->model_id, $obj), value: $obj->value);
    }
}
