<?php

namespace App\Filament\Resources\AlertResource\Pages;

use App\Filament\Resources\AlertResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAlert extends EditRecord
{
    protected static string $resource = AlertResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\Action::make('test_alert')
                ->label('Test Alert')
                ->icon('heroicon-o-play')
                ->color('info')
                ->requiresConfirmation()
                ->action(fn ($record) => $record->logTrigger('Test alert triggered', 'telegram', true)),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
