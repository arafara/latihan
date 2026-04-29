<?php

namespace App\Filament\Resources\WatchlistResource\Pages;

use App\Filament\Resources\WatchlistResource;
use Filament\Resources\Pages\CreateRecord;

class CreateWatchlist extends CreateRecord
{
    protected static string $resource = WatchlistResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Watchlist created successfully';
    }
}
