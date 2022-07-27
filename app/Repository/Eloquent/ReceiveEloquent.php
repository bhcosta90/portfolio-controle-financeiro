<?php

namespace App\Repository\Eloquent;

use App\Models\Charge;
use App\Repository\Abstracts\EloquentAbstract;
use App\Repository\Presenters\PaginatorPresenter;
use Core\Application\Charge\Modules\Receive\Domain\ReceiveEntity;
use Core\Application\Charge\Modules\Receive\Repository\ReceiveRepository;
use Core\Application\Charge\Shared\Enums\ChargeStatusEnum;
use Core\Application\Charge\Shared\Enums\ChargeTypeEnum;
use Core\Shared\Abstracts\EntityAbstract;
use Core\Shared\Interfaces\PaginationInterface;
use Core\Shared\ValueObjects\ParcelObject;
use DateTime;
use Illuminate\Support\Facades\DB;

class ReceiveEloquent extends EloquentAbstract implements ReceiveRepository
{
    protected function model()
    {
        return app(Charge::class);
    }

    public function insert(EntityAbstract $entity): bool
    {
        $obj = $this->model->create([
            'id' => $entity->id(),
            'tenant_id' => $entity->tenant,
            'group_id' => $entity->group,
            'title' => $entity->title->value,
            'resume' => $entity->resume?->value,
            'relationship_id' => $entity->customer->id,
            'relationship_type' => $entity->customer->type,
            'entity' => get_class($entity),
            'recurrence_id' => $entity->recurrence,
            'value_charge' => $entity->value->value,
            'status' => $entity->status->value,
            'date' => $entity->date->format('Y-m-d'),
            'type' => ChargeTypeEnum::DEBIT,
            'parcel_actual' => 1,
            'parcel_total' => 1,
        ]);

        return (bool)$obj;
    }

    public function insertParcel(ReceiveEntity $entity, ParcelObject $parcel): bool
    {
        $obj = $this->model->create([
            'id' => $entity->id(),
            'tenant_id' => $entity->tenant,
            'group_id' => $entity->group,
            'title' => $entity->title->value,
            'resume' => $entity->resume?->value,
            'relationship_id' => $entity->customer->id,
            'relationship_type' => $entity->customer->type,
            'entity' => get_class($entity),
            'recurrence_id' => $entity->recurrence,
            'value_charge' => $entity->value->value,
            'status' => $entity->status->value,
            'date' => $entity->date->format('Y-m-d'),
            'type' => ChargeTypeEnum::DEBIT,
            'parcel_actual' => $parcel->parcel,
            'parcel_total' => $parcel->total,
        ]);

        return (bool)$obj;
    }

    public function update(EntityAbstract $entity): bool
    {
        $obj = $this->model->findOrFail($entity->id());
        return $obj->update([
            'title' => $entity->title->value,
            'resume' => $entity->resume?->value,
            'relationship_id' => $entity->customer->id,
            'recurrence_id' => $entity->recurrence,
            'value_charge' => $entity->value->value,
            'value_pay' => $entity->pay->value,
            'status' => $entity->status->value,
            'date' => $entity->date->format('Y-m-d'),
        ]);
    }

    public function toEntity(object $obj): EntityAbstract
    {
        return ReceiveEntity::create(
            tenant: $obj->tenant_id,
            title: $obj->title,
            resume: $obj->resume,
            customer: $obj->relationship_id,
            recurrence: $obj->recurrence_id,
            value: $obj->value_charge,
            pay: $obj->value_pay,
            group: $obj->group_id,
            date: $obj->date,
            status: $obj->status,
            id: $obj->id,
            createdAt: $obj->created_at,
        );
    }

    public function filterByDate(DateTime $start, DateTime $end, array $type)
    {
        $dayFirst = clone $start;
        $dayFirst->modify('first day of this month');

        if (!count($type)) {
            $this->model = $this->model->where(fn ($q) => $q->whereBetween('charges.date', [
                $start->format('Y-m-d H:i:s'),
                $end->format('Y-m-d H:i:s')
            ])
                ->orWhere('charges.date', '<', $dayFirst->format('Y-m-d')));
        } else {
            $this->model = $this->model->where(function ($query) use ($start, $end, $type, $dayFirst) {
                if (in_array(0, $type)) {
                    $query->orWhere('charges.date', '<', $dayFirst->format('Y-m-d'));
                }

                if (in_array(1, $type)) {
                    $query->orWhereBetween('charges.date', [
                        $start->format('Y-m-d H:i:s'),
                        $end->format('Y-m-d H:i:s')
                    ]);
                }

                if (in_array(2, $type)) {
                    $endType2 = clone $end;
                    $query->orWhereBetween('charges.date', [
                        $start->format('Y-m-d H:i:s'),
                        $endType2->modify('first day of this month')
                            ->modify('1 month')
                            ->modify('last day of this month')
                            ->format('Y-m-d H:i:s')
                    ]);
                }

                if (count($type) == 1) {
                    $query->where(DB::raw('1'), '=', 2);
                }
            });
        }
    }

    public function filterByCustomerName(string $name)
    {
        $this->model = $this->model->where('relationships.name', 'like', "%{$name}%");
    }

    public function paginate(?array $filter = null, ?int $page = 1, ?int $totalPage = 15): PaginationInterface
    {
        $result = $this->where($filter)
            ->select(
                'charges.*',
                'relationships.name as relationship_name',
                'recurrences.name as recurrence_name',
            )
            ->orderBy('charges.date')
            ->orderBy('relationships.name')
            ->orderBy('recurrences.name');

        return new PaginatorPresenter($result->paginate(
            page: $page,
            perPage: $totalPage,
        ));
    }

    public function total(array $filter): float
    {
        return $this->where($filter)->sum(DB::raw('value_charge - value_pay'));
    }

    private function where(array $filter)
    {
        return $this->model
            ->join('relationships', 'relationships.id', '=', 'charges.relationship_id')
            ->leftJoin('recurrences', 'recurrences.id', '=', 'charges.recurrence_id')
            ->where('charges.entity', ReceiveEntity::class)
            ->where(fn ($q) => ($f = $filter['title'] ?? null)
                ? $q->where('charges.title', 'like', "%{$f}%")
                : null)
            ->whereIn('charges.status', [ChargeStatusEnum::PENDING]);
    }
}
