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
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Screener updated successfully';
    }
}
