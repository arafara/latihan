<?php

namespace App\Filament\Resources\ScreenerResource\Pages;

use App\Filament\Resources\ScreenerResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListScreeners extends ListRecords
{
    protected static string $resource = ScreenerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
