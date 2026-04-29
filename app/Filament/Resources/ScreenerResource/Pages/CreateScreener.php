<?php

namespace App\Filament\Resources\ScreenerResource\Pages;

use App\Filament\Resources\ScreenerResource;
use Filament\Resources\Pages\CreateRecord;

class CreateScreener extends CreateRecord
{
    protected static string $resource = ScreenerResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
