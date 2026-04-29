<?php

namespace App\Filament\Resources\ScreenerResource\Pages;

use App\Filament\Resources\ScreenerResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditScreener extends EditRecord
{
    protected static string $resource = ScreenerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\Action::make('run_now')
                ->label('Run Now')
                ->icon('heroicon-o-play')
                ->color('success')
                ->requiresConfirmation()
                ->action(fn ($record) => $record->run()),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
