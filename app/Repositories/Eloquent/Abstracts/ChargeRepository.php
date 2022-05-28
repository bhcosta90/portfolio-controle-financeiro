<?php

namespace App\Repositories\Eloquent\Abstracts;

use App\Repositories\Presenters\PaginatorPresenter;
use Carbon\Carbon;
use Costa\Modules\Charge\Entities\ChargeEntity;
use Costa\Modules\Charge\Shareds\Enums\Status;
use Costa\Modules\Charge\Shareds\ValueObjects\ParcelObject;
use Costa\Modules\Charge\Shareds\ValueObjects\ResumeObject;
use Costa\Shareds\Abstracts\EntityAbstract;
use Costa\Shareds\Contracts\PaginationInterface;
use Costa\Shareds\ValueObjects\Input\InputNameObject;
use Costa\Shareds\ValueObjects\Input\InputValueObject;
use Costa\Shareds\ValueObjects\ModelObject;
use Costa\Shareds\ValueObjects\UuidObject;
use DateTime;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

class ChargeRepository
{
    public function getResumeToday(): ResumeObject
    {
        $select = $this->getSelectJoin()
            ->where('charges.date_due', Carbon::now()->format('Y-m-d'))
            ->count();
        
        return new ResumeObject(
            quantity: $select,
            total: 0,
        );
    }

    public function getResumeDueDate(): ResumeObject
    {
        $select = $this->getSelectJoin()
            ->where('charges.date_due', '<', Carbon::now()->format('Y-m-d'))
            ->count();
        
        return new ResumeObject(
            quantity: $select,
            total: 0,
        );
    }

    public function getResumeValue(DateTime $date): ResumeObject
    {
        $select = $this->getSelectJoin()
            ->whereBetween('charges.date_due', [
                $date->modify('first day of this month')->format('Y-m-d'), 
                $date->modify('last day of this month')->format('Y-m-d'),
            ])
            ->sum('value_charge');
        
        return new ResumeObject(
            quantity: 0,
            total: $select,
        );
    }

    protected function getSelect(?array $filter = null)
    {
        
        return $this->getSelectJoin()
            ->where(fn ($q) => ($f = $filter['name'] ?? null) ? $q->where('relationships.name', 'like', "%{$f}%") : null)
            ->where(fn ($q) => $this->filterByDate($q, $filter))
            ->join('relationships', fn ($q) => $q->on('relationships.uuid', '=', 'charges.relationship_id'))
            ->leftJoin('recurrences', 'recurrences.id', '=', 'charges.recurrence_id')
            ->select('charges.*', 'relationships.name', 'recurrences.name as recurrence')
            ->whereNull('charges.deleted_at')
            ->where('charges.tenant_id', $this->getTenantId());
    }

    protected function getSelectJoin()
    {
        return $this->model
            ->join('charges', fn ($q) => $q->on('charges.charge_id', '=', $this->table . '.id')
                ->where('charges.charge_type', $this->model::class));
    }

    protected function filterByDate(\Illuminate\Database\Eloquent\Builder $q, array $filter)
    {
        $dateStart = $filter['date_start'] ?? Carbon::now()->firstOfMonth()->format('Y-m-d');
        $dateFinish = $filter['date_finish'] ?? Carbon::now()->lastOfMonth()->format('Y-m-d');
        $type = $filter['type'] ?? 0;

        $q->where(function ($query) use ($type, $dateStart, $dateFinish) {
            $query->where(function ($query) use ($dateStart, $dateFinish) {
                $query->where(fn ($query) => $query->where('charges.date_due', '>=', $dateStart))
                    ->where(fn ($query) => $query->where('charges.date_due', '<=', $dateFinish));
            })->orWhere(fn ($query) => in_array($type, [0, 2, 5]) && !empty($dateStart) ? $query->where('charges.date_due', '<', $dateStart) : null);
        });

        $q->whereNot('charges.status', Status::COMPLETED->value);

        if ($f = $filter['description'] ?? null) {
            $q->where('charges.title', 'like', "%{$f}%");
        }

        match ($filter['type'] ?? null) {
            '1' => $q->whereIn('charges.status', [Status::PENDING->value, Status::PARTIAL->value]),
            '2', '3' => $q->whereIn('charges.status', [Status::PENDING->value, Status::PARTIAL->value, Status::COMPLETED->value]),
            '4' => $q->whereIn('charges.status', [Status::COMPLETED->value]),
            '5' => $q->where('charges.date_due', '<=', Carbon::now()->format('Y-m-d'))
                ->whereIn('charges.status', [Status::PENDING->value, Status::PARTIAL->value]),
            default => $q->whereNot('charges.status', Status::COMPLETED->value),
        };
    }

    public function paginate(
        ?array $filter = null,
        ?array $order = null,
        ?int $page = 1,
        ?int $totalPage = 15
    ): PaginationInterface {
        $data = $this->getSelect($filter)
            ->orderBy('charges.date_due', 'asc')
            ->orderBy('relationships.name', 'asc')
            ->paginate(
                perPage: $totalPage,
                page: $page,
            );

        return new PaginatorPresenter($data);
    }

    public function insert(EntityAbstract $entity, ?ParcelObject $parcel = null): ChargeEntity
    {
        $objEntity = $this->model->create();

        $data = [
            'relationship_type' => $entity->relationship->type,
            'relationship_id' => $entity->relationship->id,
            'recurrence_id' => null,
            'parcel_total' => $parcel ? $parcel->total : null,
            'parcel_actual' => $parcel ? $parcel->parcel : null,
            'title' => $entity->title->value,
            'description' => $entity->description->value,
            'uuid' => $entity->id(),
            'charge_id' => $objEntity->id,
            'charge_type' => get_class($objEntity),
            'date_start' => $entity->dateStart->format('Y-m-d'),
            'date_finish' => $entity->dateFinish->format('Y-m-d'),
            'date_due' => $entity->date->format('Y-m-d'),
            'status' => $entity->status->value,
            'value_charge' => $entity->value->value,
            'group_uuid' => $entity->base,
        ];

        $objCreated = $this->charge->create($data);

        return $this->toEntity($objCreated);
    }

    public function getValueTotal(?array $filter = null): float
    {
        return $this->getSelect($filter)->sum(DB::raw('value_charge - IFNULL(value_pay, 0)'));
    }

    protected function getTenantId()
    {
        return auth()->user()->tenant_id;
    }

    public function find(int|string $id): ChargeEntity
    {
        return $this->toEntity($this->findByDb($id));
    }

    protected function findByDb(string $id)
    {
        if ($model = $this->model
            ->select('charges.*', 'relationships.name')
            ->join('charges', fn ($q) => $q->on('charges.charge_id', '=', $this->table . '.id')
                ->where('charges.charge_type', $this->model::class))
            ->join('relationships', fn ($q) => $q->on('relationships.uuid', '=', 'charges.relationship_id'))
            ->where('charges.uuid', $id)->firstOrFail()
        ) {
            return $model;
        }

        throw new NotFoundResourceException(__('Charge receive not found'));
    }

    public function insertChargeWithParcel(ChargeEntity $entity, ParcelObject $parcel)
    {
        $objEntity = $this->model->create();

        $data = [
            'relationship_type' => $entity->relationship->type,
            'relationship_id' => $entity->relationship->id,
            'recurrence_id' => null,
            'parcel_total' => $parcel ? $parcel->total : null,
            'parcel_actual' => $parcel ? $parcel->parcel : null,
            'title' => $entity->title->value,
            'description' => $entity->description->value,
            'uuid' => $entity->id(),
            'charge_id' => $objEntity->id,
            'charge_type' => get_class($objEntity),
            'date_start' => $entity->dateStart->format('Y-m-d'),
            'date_finish' => $entity->dateFinish->format('Y-m-d'),
            'date_due' => $entity->date->format('Y-m-d'),
            'status' => $entity->status->value,
            'group_uuid' => $entity->base,
            'value_charge' => $entity->value->value,
        ];

        if ($entity->recurrence) {
            $data['recurrence_id'] = $this->recurrence->where('uuid', $entity->recurrence)->first()->id;
        }

        $objCreated = $this->charge->create($data);
        return $this->toEntity($objCreated);
    }

    public function update(EntityAbstract $entity): ChargeEntity
    {
        $obj = $this->findByDb($entity->id());
        $objCharge = $this->charge->find($obj->id);

        $data = [
            'relationship_type' => $entity->relationship->type,
            'relationship_id' => $entity->relationship->id,
            'title' => $entity->title->value,
            'description' => $entity->description->value,
            'uuid' => $entity->id(),
            'value_charge' => $entity->value->value,
            'status' => $entity->status->value,
            'value_pay' => $entity->payValue->value,
        ];

        if ($entity->recurrence) {
            $data['recurrence_id'] = $this->recurrence->where('uuid', $entity->recurrence)->first()->id;
        }

        $objCharge->update($data + [
            'recurrence_id' => null
        ]);

        return $this->toEntity($objCharge);
    }

    public function toEntity(object $data): ChargeEntity
    {
        $recurrence = null;
        if ($data->recurrence_id) {
            $recurrence = new UuidObject($this->recurrence->find($data->recurrence_id)->first()->uuid);
        }

        $entity = $this->entity;

        $obj = new $entity(
            base: new UuidObject($data->group_uuid),
            title: new InputNameObject($data->title),
            description: new InputNameObject($data->description, true),
            value: new InputValueObject($data->value_charge),
            relationship: new ModelObject($data->relationship_id, $data->relationship_type, $data->name),
            date: new DateTime($data->date_due),
            dateStart: new DateTime($data->date_start),
            dateFinish: new DateTime($data->date_finish),
            id: new UuidObject($data->uuid),
            recurrence: $recurrence,
        );

        $obj->setValuePay($data->value_pay ?: 0);

        return $obj;
    }

    public function delete(int|string $id): bool
    {
        dd($id);
        
        $obj = $this->model
            ->join('charges', fn ($q) => $q->on('charges.charge_id', '=', $this->table . '.id')
                ->where('charges.charge_type', $this->model::class))
            ->select('charges.*')
            ->firstOrFail();

        $objCharge = $this->charge->find($obj->id);
        $objCharge->delete();
        return $obj->delete();
    }
}
