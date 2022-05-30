<?php

namespace Costa\Modules\Charge\Payment\UseCases;

use Costa\Modules\Charge\Payment\Entity\ChargeEntity;
use Costa\Modules\Charge\Payment\Repository\ChargeRepositoryInterface;
use Costa\Modules\Charge\Utils\Shared\ParcelCalculate;
use Costa\Modules\Charge\Utils\Shared\DTO\ParcelCalculate\Input as ParcelCalculateInput;
use Costa\Modules\Charge\Utils\ValueObject\ParcelObject;
use Costa\Modules\Recurrence\Repository\RecurrenceRepositoryInterface;
use Costa\Modules\Relationship\Supplier\Entity\SupplierEntity;
use Costa\Modules\Relationship\Supplier\Repository\SupplierRepositoryInterface;
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
        protected SupplierRepositoryInterface $relationship,
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
                $keyCache = $rs->supplierId ?? $rs->supplierName;

                /*if ($rs->supplierId && empty($cacheRelationship[$keyCache])) {
                    $cacheRelationship[$keyCache] = $this->relationship->find($rs->supplierId);
                } elseif ($rs->supplierName && empty($cacheRelationship[$keyCache])) {
                    $entityCustomer = new SupplierEntity(
                        name: new InputNameObject($rs->supplierName),
                        document: null,
                    );
                    $cacheRelationship[$keyCache] = $this->relationship->insert($entityCustomer);
                }*/
                $cacheRelationship[$keyCache] = $this->relationship->find($rs->supplierId);

                if (($objCustomer = $cacheRelationship[$keyCache]) === null) {
                    throw new Exception('Customer not found');
                }

                if ($rs->recurrence) {
                    $rs->recurrence = $this->recurrence->find((string) $rs->recurrence)->id;
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
                    recurrence: $rs->recurrence ? new UuidObject($rs->recurrence) : null,
                );

                $ret[] = new DTO\Create\Output(
                    id: $objEntity->id(),
                    title: $objEntity->title->value,
                    description: $objEntity->description->value,
                    value: $objEntity->value->value,
                    customerId: $cacheRelationship[$keyCache]->id(),
                    recurrenceId: $objEntity->recurrence,
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
