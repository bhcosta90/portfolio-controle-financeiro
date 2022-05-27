<?php

namespace Costa\Modules\Charge\UseCases\Charge;

use Costa\Modules\Charge\Shareds\ValueObjects\ParcelObject;
use Costa\Shareds\ValueObjects\Input\InputNameObject;
use Costa\Shareds\ValueObjects\Input\InputValueObject;
use Costa\Shareds\ValueObjects\ModelObject;
use Costa\Shareds\ValueObjects\UuidObject;
use Exception;
use Throwable;

abstract class ChargeCreatedUseCase
{
    public function exec(DTO\Create\Input $input): DTO\Create\Output
    {
        $objCustomer = $this->relationship->find($input->relationship->id);
        $idRecurrence = $input->recurrence ? $this->recurrence->find($input->recurrence)->id : null;

        $totalValue = 0;
        $valueCharge = $this->truncate($input->value / $input->parcel);

        for ($i = 0; $i < $input->parcel; $i++) {

            $verifyDate = clone $input->date;
            $verifyDate->modify('first day of this month');
            $verifyDate->modify('+' . $i . ' month');

            $dateParcel = clone $input->date;
            $dateParcel->modify('+' . $i . ' month');

            if ($verifyDate->format('Ym') != $dateParcel->format('Ym')) {
                $verifyDate->modify('last day of this month');
                $dateParcel = $verifyDate;
            }

            $totalValue += $this->truncate($valueCharge);

            $transactions[] = [
                'date' => $dateParcel,
                'value' => $this->truncate($valueCharge)
            ];
        }

        $valueChargeInput = $this->truncate($input->value);

        if ($totalValue < $valueChargeInput) {
            $calc = $valueChargeInput - $totalValue;
            $transactions[$input->parcel - 1]['value'] = $this->truncate($transactions[$input->parcel - 1]['value'] + $calc);
            $totalValue = $totalValue + $calc;
        } elseif ($totalValue > $valueChargeInput) {
            throw new Exception('Favor calcular esse valor com o total de parcelas: ' . $input->parcel . ', com o valor de ' . $input->value);
        }

        $ret = [];

        $base = UuidObject::random();

        try {
            foreach ($transactions as $k => $transaction) {
                $entity = $this->entity;

                $objEntity = new $entity(
                    base: $base,
                    title: new InputNameObject($input->title),
                    description: new InputNameObject($input->description, true),
                    relationship: new ModelObject($objCustomer->id(), $objCustomer),
                    value: new InputValueObject($transaction['value']),
                    date: $transaction['date'],
                    dateStart: $transactions[0]['date'],
                    dateFinish: $transactions[$input->parcel - 1]['date'],
                    recurrence: $idRecurrence,
                );

                $ret[] = $this->repo->insertChargeWithParcel($objEntity, new ParcelObject(count($transactions), $k + 1));
            }
            $this->transaction->commit();
        } catch (Throwable $e) {
            $this->transaction->rollback();
            throw $e;
        }

        return new DTO\Create\Output(charges: $ret);
    }

    private function truncate($value, $precision = 2)
    {
        return intval($value * ($p = pow(10, $precision))) / $p;
    }
}
