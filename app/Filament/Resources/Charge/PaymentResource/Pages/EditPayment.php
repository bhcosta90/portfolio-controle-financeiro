<?php

namespace App\Filament\Resources\Charge\PaymentResource\Pages;

use App\Filament\Resources\Charge\PaymentResource;
use App\Filament\Resources\Charge\Traits\ResourceTrait;
use App\Models\Charge\Charge;
use App\Models\Enum\Charge\TypeEnum;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Enums\Alignment;
use Illuminate\Database\Eloquent\Model;

class EditPayment extends EditRecord
{
    use ResourceTrait;

    public Charge | Model | int | string | null $record;

    protected static string $resource = PaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            $this->record->type == TypeEnum::UNIQUE
                ? Actions\DeleteAction::make()
                : $this->deleteOnModal(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }

    protected function deleteOnModal(): Actions\Action
    {
        return Actions\Action::make('edit')
            ->label(__('filament-actions::delete.single.label'))
            ->color('danger')
            ->action(null)
            ->modalWidth('xl')
            ->modalAlignment(Alignment::Center)
            ->modalIcon('heroicon-o-trash')
            ->modalHeading(__('Deletar cobrança'))
            ->modalDescription(__('Tem certeza de que deseja excluir esta cobrança? Isto não pode ser desfeito.'))
            ->modalCancelAction(false)
            ->modalSubmitAction(false)
            ->extraModalFooterActions([
                Actions\Action::make('deleteOnThis')
                    ->color('danger')
                    ->label(__('Deletar somente essa'))
                    ->action(function(){
                        $this->record->deleteOnlyThis();
                        $this->redirect($this->getResource()::getUrl('index'));
                    }),
                Actions\Action::make('deleteThisAndNext')
                    ->color('danger')
                    ->label(__('Deletar esta e as próximas'))
                    ->action(function(){
                        $this->record->deleteThisAndNext();
                        $this->redirect($this->getResource()::getUrl('index'));
                    }),
                Actions\Action::make('deleteAll')
                    ->color('danger')
                    ->label(__('Deletar todas'))
                    ->action(function(){
                        $this->record->deleteAll();
                        $this->redirect($this->getResource()::getUrl('index'));
                    }),
            ]);
    }
}
