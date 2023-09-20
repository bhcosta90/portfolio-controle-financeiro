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

        $groupId = str()->uuid();
        if ($data['type'] == TypeEnum::PARCEL->value) {
            $charges = $this->generateParcel(
                value: $data['value'],
                type: ParcelEnum::from($data['parcel_type']),
                quantityParcel: $data['parcel_quantity'],
                date: now()->parse($data['due_date']),
                description: $data['description']
            );

            $firstRecord = null;
            foreach ($charges as $charge) {
                $this->record = $this->handleRecordCreation(
                    $charge + $data + [
                        'group_id' => $groupId
                    ]
                );
                $this->form->model($this->getRecord())->saveRelationships();

                if ($firstRecord === null) {
                    $firstRecord = $this->record;
                }
            }

            $this->record = $firstRecord;
        } else {
            $this->record = $this->handleRecordCreation(
                $data + [
                    'group_id' => $groupId
                ]
            );
            $this->form->model($this->getRecord())->saveRelationships();
        }

        $this->callHook('afterCreate');
    }
}
