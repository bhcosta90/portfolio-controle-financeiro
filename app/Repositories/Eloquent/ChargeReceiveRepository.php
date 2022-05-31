<?php

namespace App\Repositories\Eloquent;

use App\Models\Charge;
use App\Repositories\Presenters\PaginatorPresenter;
use Carbon\Carbon;
use Costa\Modules\Charge\Receive\Entity\ChargeEntity;
use Costa\Modules\Charge\Receive\Repository\ChargeRepositoryInterface;
use Costa\Modules\Charge\Utils\Enums\ChargeStatusEnum;
use Costa\Modules\Charge\Utils\ValueObject\ParcelObject;
use Costa\Modules\Relationship\Customer\Entity\CustomerEntity;
use Costa\Shared\Abstracts\EntityAbstract;
use Costa\Shared\Contracts\PaginationInterface;
use Costa\Shared\ValueObject\Input\InputNameObject;
use Costa\Shared\ValueObject\Input\InputValueObject;
use Costa\Shared\ValueObject\ModelObject;
use Costa\Shared\ValueObject\UuidObject;
use DateTime;
use Illuminate\Support\Facades\DB;

class ChargeReceiveRepository implements ChargeRepositoryInterface
{
    public function __construct(
        protected Charge $model,
    ) {
        //  
    }

    protected function entity(object $entity)
    {
        return new ChargeEntity(
            title: new InputNameObject($entity->title),
            base: new UuidObject($entity->uuid),
            description: new InputNameObject($entity->description, true),
            customer: new ModelObject($entity->relationship_id, $entity->relationship_type),
            value: new InputValueObject($entity->value_charge),
            date: new DateTime($entity->date_due),
            dateStart: new DateTime($entity->date_start),
            dateFinish: new DateTime($entity->date_finish),
            recurrence: $entity->recurrence_id ? new UuidObject($entity->recurrence_id) : null,
            id: new UuidObject($entity->id),
            createdAt: new DateTime($entity->created_at),
            payValue: new InputValueObject($entity->value_pay, true),
            status: ChargeStatusEnum::from($entity->status),
        );
    }

    public function insert(EntityAbstract $entity): EntityAbstract
    {
        $obj = $this->model->create([
            'id' => $entity->id(),
            'recurrence_id' => $entity->recurrence,
            'relationship_id' => $entity->customer?->id,
            'relationship_type' => $entity->customer?->type,
            'uuid' => $entity->base,
            'title' => $entity->title->value,
            'description' => $entity->description?->value,
            'date_start' => $entity->date->format('Y-m-d'),
            'date_finish' => $entity->dateStart->format('Y-m-d'),
            'date_due' => $entity->dateFinish->format('Y-m-d'),
            'parcel_total' => 0,
            'parcel_actual' => 0,
            'status' => $entity->status->value,
            'value_charge' => $entity->value->value,
            'value_pay' => 0,
            'entity' => get_class($entity),
        ]);

        return $this->entity($obj);
    }

    public function update(EntityAbstract $entity): EntityAbstract
    {
        $obj = $this->findDb($entity->id);

        $obj->update([
            'title' => $entity->title->value,
            'description' => $entity->description?->value,
            'date_due' => $entity->date->format('Y-m-d'),
            'relationship_id' => $entity->customer?->id,
            'relationship_type' => $entity->customer?->type,
            'value_charge' => $entity->value->value,
            'recurrence_id' => $entity->recurrence,
            'status' => $entity->status->value,
            'value_pay' => $entity->payValue->value,
        ]);

        return $this->entity($obj);
    }

    public function find(string|int $key): EntityAbstract
    {
        return $this->entity($this->findDb($key));
    }

    public function findDb(string|int $key): object|array
    {
        return $this->model->where('entity', ChargeEntity::class)->where('id', $key)->first();
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
        $result = $this->toSql($filter)
            ->orderBy('charges.date_due', 'asc');

        return new PaginatorPresenter($result->paginate(
            page: $page,
            perPage: $totalPage,
        ));
    }

    public function all(?array $filter = null): array|object
    {
        return $this->toSql($filter)->get();
    }

    public function total(?array $filter = null): float
    {
        return $this->toSql($filter)->sum(DB::raw('charges.value_charge - charges.value_pay'));
    }

    public function pluck(): array
    {
        return $this->toSql([])->orderBy('name', 'asc')->pluck('name', 'id')->toArray();
    }

    public function insertWithParcel(ChargeEntity $entity, ParcelObject $parcel): ChargeEntity
    {
        $obj = $this->model->create([
            'id' => $entity->id(),
            'recurrence_id' => $entity->recurrence,
            'relationship_id' => $entity->customer?->id,
            'relationship_type' => $entity->customer?->type,
            'uuid' => $entity->base,
            'title' => $entity->title->value,
            'description' => $entity->description?->value,
            'date_start' => $entity->date->format('Y-m-d'),
            'date_finish' => $entity->dateStart->format('Y-m-d'),
            'date_due' => $entity->dateFinish->format('Y-m-d'),
            'parcel_total' => $parcel->total,
            'parcel_actual' => $parcel->actual,
            'status' => $entity->status->value,
            'value_charge' => $entity->value->value,
            'value_pay' => 0,
            'entity' => get_class($entity),
        ]);

        return $this->entity($obj);
    }

    private function toSql(?array $filter = null)
    {
        return $this->model
            ->select('charges.*', 'relationships.name as relationship_name')
            ->join('relationships', fn ($q) => $q->on('relationships.id', '=', 'charges.relationship_id')
                ->where('relationships.entity', CustomerEntity::class))
            ->where('charges.entity', ChargeEntity::class)
            ->where(fn ($q) => ($f = $filter['name'] ?? null) ? $q->where('relationships.name', 'like', "%{$f}%") : null)
            ->where(fn ($q) => $this->filterByDate($q, $filter));
    }

    private function filterByDate(\Illuminate\Database\Eloquent\Builder $q, ?array $filter = [])
    {
        $dateParam = $filter['month'] ?? null;

        if ($dateParam) {
            $dateStart = $filter['date_start'] ?? (new Carbon(
                str_pad($dateParam, 10, "01-", STR_PAD_LEFT)
            ))->firstOfMonth()->format('Y-m-d');
            $dateFinish = $filter['date_finish'] ?? (new Carbon(
                str_pad($dateParam, 10, "01-", STR_PAD_LEFT)
            ))->lastOfMonth()->format('Y-m-d');
        } else {
            $dateStart = $filter['date_start'] ?? Carbon::now()->firstOfMonth()->format('Y-m-d');
            $dateFinish = $filter['date_finish'] ?? Carbon::now()->lastOfMonth()->format('Y-m-d');
        }

        $type = $filter['type'] ?? 0;

        $q->where(function ($query) use ($type, $dateStart, $dateFinish) {
            $query->where(function ($query) use ($dateStart, $dateFinish) {
                $query->where(fn ($query) => $query->where('charges.date_due', '>=', $dateStart))
                    ->where(fn ($query) => $query->where('charges.date_due', '<=', $dateFinish));
            })->orWhere(fn ($query) => in_array($type, [0, 2, 5]) && !empty($dateStart) ? $query->where('charges.date_due', '<', $dateStart) : null);
        });

        match ($filter['type'] ?? null) {
            '1' => $q->whereIn('charges.status', [ChargeStatusEnum::PENDING->value, ChargeStatusEnum::PARTIAL->value]),
            '2', '3' => $q->whereIn('charges.status', [ChargeStatusEnum::PENDING->value, ChargeStatusEnum::PARTIAL->value, ChargeStatusEnum::COMPLETED->value]),
            '4' => $q->whereIn('charges.status', [ChargeStatusEnum::COMPLETED->value]),
            '5' => $q->where('charges.date_due', '<=', Carbon::now()->format('Y-m-d'))
                ->whereIn('charges.status', [ChargeStatusEnum::PENDING->value, ChargeStatusEnum::PARTIAL->value]),
            default => $q->whereNot('charges.status', ChargeStatusEnum::COMPLETED->value),
        };
    }
}
