<?php

namespace App\Filament\Resources\Charge\PaymentResource\Pages;

use App\Filament\Resources\Charge\PaymentResource;
use App\Filament\Resources\Charge\Traits\ResourceTrait;
use App\Filament\Resources\Charge\Traits\SaveTrait;
use App\Models\Enum\Charge\ParcelEnum;
use App\Models\Enum\Charge\TypeEnum;
use Filament\Resources\Pages\CreateRecord;

class CreatePayment extends CreateRecord
{
    use ResourceTrait, SaveTrait;

    protected static string $resource = PaymentResource::class;

    protected function createRecordAndCallHooks(array $data): void
    {
        $this->callHook('beforeCreate');

        $data['description'] = $data['description'] ?? __('Outros');

        if ($data['type'] == TypeEnum::PARCEL->value) {
            $calculate = $data['value'];
            if ($data['parcel_type'] == ParcelEnum::TOTAL->value) {
                $calculate = $calculate / $data['parcel_quantity'];
            }

            $charges = [];
            $rest = 0;
            $dateStart = now()->parse($data['due_date']);
            $dayStart = $dateStart->format('d');

            for ($i = 0; $i < $data['parcel_quantity']; $i++) {
                $response = [
                    'type' => TypeEnum::UNIQUE,
                    'value' => str()->truncate($calculate),
                    'description' => $data['description'] . " " . ($i + 1) . '/' . $data['parcel_quantity'],
                ];

                $rest += str()->truncate($calculate);

                $month = clone $dateStart;
                $dateCharge = clone $dateStart;

                $month->firstOfMonth()->addMonth($i);
                $dateCharge->firstOfMonth()->addMonth($i)->setDay($dayStart);

                if ($dateCharge->format('Ym') != $month->format('Ym')) {
                    $dateCharge->subMonth()->lastOfMonth();
                }

                $response['date'] = $dateCharge->format('Y-m-d');

                $charges[] = $response;
            }

            $rest = str()->truncate(str()->truncate($data['value']) - str()->truncate($rest));
            $charges[count($charges) - 1]['value'] = $charges[count($charges) - 1]['value'] + $rest;

            $firstRecord = null;
            foreach ($charges as $charge) {
                $this->record = $this->handleRecordCreation($charge + $data);
                $this->form->model($this->getRecord())->saveRelationships();

                if ($firstRecord === null) {
                    $firstRecord = $this->record;
                }
            }

            $this->record = $firstRecord;
        } else {
            $this->record = $this->handleRecordCreation($data);
            $this->form->model($this->getRecord())->saveRelationships();
        }

        $this->callHook('afterCreate');
    }
}
