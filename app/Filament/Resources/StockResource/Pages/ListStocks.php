<?php

namespace App\Filament\Resources\StockResource\Pages;

use App\Filament\Resources\StockResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStocks extends ListRecords
{
    protected static string $resource = StockResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\Action::make('import_watchlist')
                ->label('Import Watchlist')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->url(route('filament.admin.pages.import-watchlist')),
        ];
    }
}
