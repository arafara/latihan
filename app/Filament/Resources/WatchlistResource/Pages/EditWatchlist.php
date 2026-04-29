<?php

namespace App\Filament\Resources\WatchlistResource\Pages;

use App\Filament\Resources\WatchlistResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWatchlist extends EditRecord
{
    protected static string $resource = WatchlistResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\Action::make('view_stocks')
                ->label('View Stocks')
                ->icon('heroicon-o-eye')
                ->url(fn ($record) => route('filament.admin.resources.stocks.index'))
                ->color('info'),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Watchlist updated successfully';
    }
}
