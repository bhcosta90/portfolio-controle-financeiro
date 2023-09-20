<?php

namespace App\Filament\Resources\Charge\Traits;

use App\Models\Charge\Charge;
use App\Models\Enum\Charge\ParcelEnum;
use App\Models\Enum\Charge\TypeEnum;
use Filament\Actions;
use Filament\Support\Enums\Alignment;
use Illuminate\Database\Eloquent\Model;

trait EditTrait
{
    use SaveTrait;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        return [
                'parcel_quantity' => 1,
                'parcel_type' => ParcelEnum::TOTAL->value,
            ] + $data;
    }

    protected function deleteOnModal(): Actions\Action
    {
        $title = self::getResource()::getLabel();

        return Actions\Action::make('edit')
            ->label(__('filament-actions::delete.single.label'))
            ->color('danger')
            ->action(null)
            ->modalWidth('xl')
            ->modalAlignment(Alignment::Center)
            ->modalIcon('heroicon-o-trash')
            ->modalHeading(__("Deletar " . $title))
            ->modalDescription(
                __('Tem certeza de que deseja excluir esta :title? Isto não pode ser desfeito.', [
                    'title' => $title
                ])
            )
            ->modalCancelAction(false)
            ->modalSubmitAction(false)
            ->extraModalFooterActions([
                Actions\Action::make('deleteOnThis')
                    ->color('danger')
                    ->label(__('Deletar somente essa'))
                    ->action(function () {
                        $this->record->deleteOnlyThis();
                        $this->redirect($this->getResource()::getUrl('index'));
                    }),
                Actions\Action::make('deleteThisAndNext')
                    ->color('danger')
                    ->label(__('Deletar esta e as próximas'))
                    ->action(function () {
                        $this->record->deleteThisAndNext();
                        $this->redirect($this->getResource()::getUrl('index'));
                    }),
                Actions\Action::make('deleteAll')
                    ->color('danger')
                    ->label(__('Deletar todas'))
                    ->action(function () {
                        $this->record->deleteAll();
                        $this->redirect($this->getResource()::getUrl('index'));
                    }),
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            $this->record->type == TypeEnum::UNIQUE->value && empty($this->record->is_parcel)
                ? Actions\DeleteAction::make()
                : $this->deleteOnModal(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }

    protected function getRedirectUrl(): ?string
    {
        return $this->getResource()::getUrl('edit', ['record' => $this->record->charge->id]);
    }

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
