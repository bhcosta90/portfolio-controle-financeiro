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

    /**
     * @param Charge $record
     * @param array $data
     * @return Charge
     */
    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        DB::transaction(function () use ($record, $data) {
            if ($data['type'] == TypeEnum::PARCEL->value) {
                $firstRecord = $this->createChargeWithParcel($data, $record);
                $this->record = $firstRecord;
            } else {
                $record->update($data);
                $this->record = $record;
            }
        });

        if (!empty($data['__type']) && $data['__type'] == 'save_next') {
            $this->updateThisAndNextCharge($data['due_date'], $record);
        }

        return $this->record;
    }

    /**
     * @param array $data
     * @param Model|Charge $record
     * @return Charge
     */
    protected function createChargeWithParcel(array $data, Model|Charge $record): Charge
    {
        $charges = $this->generateParcel(
            value: $data['value'],
            type: ParcelEnum::from($data['parcel_type']),
            quantity: $data['parcel_quantity'],
            date: now()->parse($data['due_date']),
            description: $data['description']
        );

        $record->update(['group_id' => $groupId = str()->uuid()] + $charges[0] + $data);
        unset($charges[0]);

        /**
         * @var Charge $firstRecord
         */
        $firstRecord = $this->record;
        foreach ($charges as $charge) {
            $this->record = $this->handleRecordCreation(
                $charge + $data + [
                    'group_id' => $groupId,
                ]
            );
            $this->form->model($this->getRecord())->saveRelationships();
        }
        return $firstRecord;
    }

    /**
     * @param $due_date
     * @param Model|Charge $record
     * @return void
     */
    protected function updateThisAndNextCharge($due_date, Model|Charge $record): void
    {
        $dateActual = now()->parse($due_date);
        $dayActual = $dateActual->format('d');
        $updateData = $this->data;
        unset($updateData['description']);

        Charge::where('group_id', $record->group_id)
            ->where('due_date', '>', $record->due_date)
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

    protected function verifyModal(): bool
    {
        return $this->record->type == TypeEnum::UNIQUE->value && empty($this->record->is_parcel);
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

}
