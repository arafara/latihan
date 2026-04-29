<?php

namespace App\Filament\Widgets;

use App\Models\Stock;
use App\Models\Watchlist;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class MarketOverviewWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Stocks', Stock::count())
                ->description('Tracked stocks')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color('primary')
                ->chart([7, 3, 4, 5, 6, 3, 5])
                ->url(route('filament.admin.resources.stocks.index')),

            Stat::make('Watchlists', Watchlist::count())
                ->description('Active watchlists')
                ->descriptionIcon('heroicon-m-eye')
                ->color('success')
                ->chart([2, 4, 3, 5, 7, 6, 8])
                ->url(route('filament.admin.resources.watchlists.index')),

            Stat::make('Active Screeners', \App\Models\Screener::where('is_active', true)->count())
                ->description('Running screeners')
                ->descriptionIcon('heroicon-m-funnel')
                ->color('info')
                ->url(route('filament.admin.resources.screeners.index')),

            Stat::make('Active Alerts', \App\Models\Alert::where('is_active', true)->count())
                ->description('Monitoring alerts')
                ->descriptionIcon('heroicon-m-bell')
                ->color('warning')
                ->url(route('filament.admin.resources.alerts.index')),
        ];
    }

    protected function getColumns(): int
    {
        return 2;
    }
}
