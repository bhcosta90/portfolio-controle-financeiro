<?php

namespace Costa\Modules\Charge\Receive\UseCases;

use Costa\Modules\Charge\Receive\Entity\ChargeEntity;
use Costa\Modules\Charge\Receive\Repository\ChargeRepositoryInterface;
use Costa\Modules\Charge\Utils\Shared\ParcelCalculate;
use Costa\Modules\Charge\Utils\Shared\DTO\ParcelCalculate\Input as ParcelCalculateInput;
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
            foreach ($parcel as $rsParcel) {
                $keyCache = $rs->customerId ?? $rs->customerName;

                if ($rs->customerId && empty($cacheRelationship[$keyCache])) {
                    $cacheRelationship[$keyCache] = $this->relationship->find($rs->customerId);
                } elseif ($rs->customerName && empty($cacheRelationship[$keyCache])) {
                    $entityCustomer = new CustomerEntity(
                        name: new InputNameObject($rs->customerName),
                        document: null,
                    );
                    $cacheRelationship[$keyCache] = $this->relationship->insert($entityCustomer);
                }

                if (($objCustomer = $cacheRelationship[$keyCache]) === null) {
                    throw new Exception('Customer not found');
                }

                $objEntity = new ChargeEntity(
                    title: new InputNameObject($rs->title),
                    description: new InputNameObject($rs->description, true),
                    relationship: new ModelObject($objCustomer->id(), $objCustomer),
                    value: new InputValueObject($rsParcel->value),
                    date: $rsParcel->date,
                    base: new UuidObject($uuid),
                    dateStart: $parcel[0]->date,
                    dateFinish: end($parcel)->date,
                    recurrence: $rs->recurrence,
                );

                $create[] = $objEntity;

                $ret[] = new DTO\Create\Output(
                    id: $objEntity->id(),
                    title: $objEntity->title->value,
                    description: $objEntity->description->value,
                    value: $objEntity->value->value,
                    customerId: $cacheRelationship[$keyCache]->id(),
                );
            }

            $charges[] = $ret;
        }

        try {
            foreach ($create as $rs) {
                $this->repo->insert($rs);
                $ret[] = new DTO\Create\Output(
                    id: $objEntity->id(),
                    title: $objEntity->title->value,
                    description: $objEntity->description->value,
                    value: $objEntity->value->value,
                    customerId: $cacheRelationship[$keyCache]->id(),
                );
            }
            
            $this->transaction->commit();

        } catch (Throwable $e) {
            $this->transaction->rollback();
            throw $e;
        }

        return $charges;
    }
}
