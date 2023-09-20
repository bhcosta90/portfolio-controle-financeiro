<?php

namespace App\Filament\Resources\Charge\PaymentResource\Pages;

use App\Filament\Resources\Charge\PaymentResource;
use App\Filament\Resources\Charge\Traits\EditTrait;
use App\Filament\Resources\Charge\Traits\ResourceTrait;
use App\Models\Charge\Charge;
use App\Models\Enum\Charge\ParcelEnum;
use App\Models\Enum\Charge\TypeEnum;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditPayment extends EditRecord
{
    use ResourceTrait, EditTrait;

    public Charge|Model|int|string|null $record;

    protected static string $resource = PaymentResource::class;

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        if ($data['type'] == TypeEnum::PARCEL->value) {
            $charges = $this->generateParcel(
                value: $data['value'],
                type: ParcelEnum::from($data['parcel_type']),
                quantityParcel: $data['parcel_quantity'],
                date: now()->parse($data['due_date']),
                description: $data['description']
            );
            $record->update($charges[0] + $data);
            unset($charges[0]);

            /**
             * @var Charge $firstRecord
             */
            $firstRecord = $this->record;
            foreach ($charges as $charge) {
                $this->record = $this->handleRecordCreation(
                    $charge + $data + [
                        'group_id' => $firstRecord->group_id,
                    ]
                );
                $this->form->model($this->getRecord())->saveRelationships();
            }
            $this->record = $firstRecord;
        } else {
            $record->update($data);
        }

        return $record;
    }
}
