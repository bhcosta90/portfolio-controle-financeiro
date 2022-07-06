<?php

namespace Core\Application\Charge\Modules\Payment\Services;

use Core\Application\Charge\Modules\Payment\Domain\PaymentEntity as Entity;
use Core\Application\Charge\Modules\Payment\Repository\ChargePaymentRepository as Repo;
use Core\Application\Charge\Modules\Recurrence\Repository\RecurrenceRepository;
use Core\Application\Relationship\Modules\Company\Repository\CompanyRepository as RelationshipRepository;
use Core\Application\Relationship\Shared\Exceptions\RelationshipException;
use Core\Shared\Interfaces\TransactionInterface;
use Core\Shared\Support\DTO\ParcelCalculate\Input;
use Core\Shared\Support\ParcelCalculate;
use Core\Shared\ValueObjects\ParcelObject;
use DateTime;
use Exception;
use Ramsey\Uuid\Uuid;
use Throwable;

class CreateService
{
    public function __construct(
        private Repo $repository,
        private RelationshipRepository $relationship,
        private RecurrenceRepository $recurrence,
        private TransactionInterface $transaction,
    ) {
        //
    }

    /** @return DTO\Create\Output[] */
    public function handle(DTO\Create\Input $input): array
    {
        if (!$this->relationship->exist($input->company)) {
            throw new RelationshipException('Company not found');
        }

        if ($input->recurrence && !$this->recurrence->exist($input->recurrence)) {
            throw new Exception('Recurrence not found');
        }

        $parcels = new ParcelCalculate();
        $dataParcel = $parcels->handle(new Input($input->parcel, $input->value, new DateTime($input->date)));

        $ret = [];
        $group = Uuid::uuid4();

        try {
            foreach ($dataParcel as $k => $rs) {

                $entity = Entity::create(
                    tenant: $input->tenant,
                    title: $input->title,
                    resume: $input->resume,
                    company: $input->company,
                    recurrence: $input->recurrence,
                    value: $rs->value,
                    date: $rs->date->format('Y-m-d'),
                    group: $group,
                    pay: 0,
                );

                $this->repository->insertParcel($entity, $parcel = new ParcelObject($k + 1, count($dataParcel)));

                $ret[] = new DTO\Create\Output(
                    title: $entity->title->value,
                    resume: $entity->resume?->value,
                    company: (string) $entity->company->id,
                    recurrence: (string) $entity->recurrence,
                    value: $entity->value->value,
                    date: $entity->date->format('Y-m-d'),
                    group: (string) $entity->group,
                    id: $entity->id(),
                    parcel: $parcel
                );
            }
            $this->transaction->commit();
            return $ret;
        } catch (Throwable $e) {
            $this->transaction->rollback();
            throw $e;
        }
    }
}
