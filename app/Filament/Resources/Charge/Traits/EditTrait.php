<?php

namespace App\Filament\Resources\Charge\Traits;

use App\Models\Enum\Charge\TypeEnum;
use Filament\Actions;
use Filament\Support\Enums\Alignment;

trait EditTrait
{
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
            ->modalDescription(__('Tem certeza de que deseja excluir esta :title? Isto não pode ser desfeito.', [
                'title' => $title
            ]))
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

    protected function getHeaderActions(): array
    {
        return [
            $this->record->type == TypeEnum::UNIQUE->value
                ? Actions\DeleteAction::make()
                : $this->deleteOnModal(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
