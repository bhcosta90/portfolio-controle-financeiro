<?php

namespace App\Filament\Resources\Charge\Traits;

use App\Models\Charge\Charge;
use App\Models\Enum\Charge\ParcelEnum;
use App\Models\Enum\Charge\TypeEnum;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Support\Enums\Alignment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

trait EditTrait
{
    use ParcelTrait;

    public function saveNext(): void
    {
        $this->handleRecordUpdate($this->getRecord(), $this->data + ['__type' => 'save_next']);
        $this->sendSavedNotificationAndRedirect();
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        return [
                'parcel_quantity' => 1,
                'parcel_type' => ParcelEnum::TOTAL->value,
            ] + $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            $this->verifyModal()
                ? Actions\DeleteAction::make()
                : $this->deleteOnModal(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
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

    protected function getRedirectUrl(): ?string
    {
        return $this->getResource()::getUrl('edit', ['record' => $this->record->charge->id]);
    }

    protected function handleRecordUpdateOld(Model $record, array $data): Model
    {
        return DB::transaction(function () use ($record, $data) {
            if (!empty($data['__type']) && $data['__type'] == 'save_next') {
                $dateActual = now()->parse($data['due_date']);
                $dayActual = $dateActual->format('d');
                $updateData = $this->data;
                unset($updateData['description']);

                Charge::where('group_id', $this->record->group_id)
                    ->where('due_date', '>', $this->record->due_date)
                    ->whereNull('is_payed')
                    ->chunk(100, function ($charges) use ($dayActual, $updateData) {
                        foreach ($charges as $charge) {
                            $date = now()->parse($charge->due_date)->setDay($dayActual);
                            if ($date->format('d') != $dayActual) {
                                $date->subMonth()->lastOfMonth();
                            }

                            $updateData['due_date'] = $date->format('Y-m-d');
                            $charge->update($updateData);
                        }
                    });
            }

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
                $this->record = $record;
            }

            return $this->record;
        });
    }

    protected function getFormActions(): array
    {
        $data = [
            $this->getSaveFormAction()
        ];

        if (!$this->verifyModal()) {
            $data[] = $this->getSaveAndNextFormAction();
        }

        return array_merge($data, [
            $this->getCancelFormAction(),
        ]);
    }

    protected function getSaveAndNextFormAction(): Action
    {
        return Action::make('save_next')
            ->label(__('Salvar essa e as próximas'))
            ->action('saveNext');
    }

    protected function verifyModal(): bool
    {
        return $this->record->type == TypeEnum::UNIQUE->value && empty($this->record->is_parcel);
    }

}
