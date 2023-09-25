<?php

namespace App\Filament\Resources\Charge\Traits;

use App\Models\Enum\Charge\ParcelEnum;
use App\Models\Enum\Charge\TypeEnum;

trait CreateTrait
{
    use ParcelTrait;

    protected function createRecordAndCallHooks(array $data): void
    {
        $this->callHook('beforeCreate');

        $data['description'] = $data['description'] ?? __('Outros');

        $groupId = str()->uuid();
        if ($data['type'] == TypeEnum::PARCEL->value) {
            $charges = $this->generateParcel(
                value: $data['value'],
                type: ParcelEnum::from($data['parcel_type']),
                quantity: $data['parcel_quantity'],
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
