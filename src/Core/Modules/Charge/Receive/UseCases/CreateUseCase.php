<?php

namespace Costa\Modules\Charge\Receive\UseCases;

use Costa\Modules\Charge\Receive\Entity\ChargeEntity;
use Costa\Modules\Charge\Receive\Repository\ChargeRepositoryInterface;
use Costa\Modules\Charge\Utils\Shared\ParcelCalculate;
use Costa\Modules\Charge\Utils\Shared\DTO\ParcelCalculate\Input as ParcelCalculateInput;
use Costa\Modules\Charge\Utils\ValueObject\ParcelObject;
use Costa\Modules\Recurrence\Repository\RecurrenceRepositoryInterface;
use Costa\Modules\Relationship\Customer\Entity\CustomerEntity;
use Costa\Modules\Relationship\Customer\Repository\CustomerRepositoryInterface;
use Costa\Shared\Contracts\TransactionContract;
use Costa\Shared\ValueObject\Input\InputNameObject;
use Costa\Shared\ValueObject\Input\InputValueObject;
use Costa\Shared\ValueObject\ModelObject;
use Costa\Shared\ValueObject\UuidObject;
use Exception;
use Throwable;

class CreateUseCase
{
    public function __construct(
        protected ChargeRepositoryInterface $repo,
        protected CustomerRepositoryInterface $relationship,
        protected RecurrenceRepositoryInterface $recurrence,
        protected TransactionContract $transaction,
    ) {
        //
    }

    /** @param DTO\Create\Input $input[] */
    public function handle(array $input): array
    {
        $charges = [];
        $uuid = str()->uuid();
        $create = [];

        $cacheRelationship = [];
        foreach ($input as $rs) {
            $ret = [];
            $parcel = (new ParcelCalculate)->handle(new ParcelCalculateInput($rs->parcel, $rs->value, $rs->date));
            /**/
            foreach ($parcel as $k => $rsParcel) {
                $keyCache = $rs->customer ?? $rs->customerName;

                /*if ($rs->customer && empty($cacheRelationship[$keyCache])) {
                    $cacheRelationship[$keyCache] = $this->relationship->find($rs->customer);
                } elseif ($rs->customerName && empty($cacheRelationship[$keyCache])) {
                    $entityCustomer = new SupplierEntity(
                        name: new InputNameObject($rs->customerName),
                        document: null,
                    );
                    $cacheRelationship[$keyCache] = $this->relationship->insert($entityCustomer);
                }*/
                
                $cacheRelationship[$keyCache] = $this->relationship->find($rs->customer);

                if (($objCustomer = $cacheRelationship[$keyCache]) === null) {
                    throw new Exception('Customer not found');
                }

                if ($rs->recurrence) {
                    $rs->recurrence = $this->recurrence->find((string) $rs->recurrence)->id;
                }

                $objEntity = new ChargeEntity(
                    title: new InputNameObject($rs->title),
                    description: new InputNameObject($rs->description, true),
                    customer: new ModelObject($objCustomer->id(), $objCustomer),
                    value: new InputValueObject($rsParcel->value),
                    date: $rsParcel->date,
                    base: new UuidObject($uuid),
                    dateStart: $parcel[0]->date,
                    dateFinish: end($parcel)->date,
                    recurrence: $rs->recurrence ? new UuidObject($rs->recurrence) : null,
                );

                $ret[] = new DTO\Create\Output(
                    id: $objEntity->id(),
                    title: $objEntity->title->value,
                    description: $objEntity->description->value,
                    value: $objEntity->value->value,
                    customer_id: $cacheRelationship[$keyCache]->id(),
                    recurrence_id: $objEntity->recurrence,
                );

                try {
                    $this->repo->insertWithParcel($objEntity, new ParcelObject($rs->parcel, $k + 1));
                    $this->transaction->commit();
                }catch(Throwable $e) {
                    $this->transaction->rollback();
                    throw $e;
                }
            }

            $charges[] = $ret;
        }

        return $charges;
    }
}
