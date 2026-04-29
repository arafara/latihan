<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use App\Models\Stock;
use App\Models\Watchlist;
use App\Models\Alert;
use App\Models\Screener;

class MarketOverviewWidget extends Widget
{
    protected static ?int $sort = 1;

    protected int | string | array $columnSpan = 'full';

    protected static string $view = 'filament.widgets.market-overview-widget';

    public function getStats(): array
    {
        return [
            [
                'label' => 'Total Stocks',
                'value' => Stock::count(),
                'icon' => 'heroicon-o-chart-bar',
                'color' => 'primary',
                'description' => 'Tracked in database',
            ],
            [
                'label' => 'Watchlists',
                'value' => Watchlist::count(),
                'icon' => 'heroicon-o-eye',
                'color' => 'success',
                'description' => 'Active watchlists',
            ],
            [
                'label' => 'Active Alerts',
                'value' => Alert::where('is_active', true)->count(),
                'icon' => 'heroicon-o-bell',
                'color' => 'warning',
                'description' => 'Monitoring',
            ],
            [
                'label' => 'Screeners',
                'value' => Screener::where('is_active', true)->count(),
                'icon' => 'heroicon-o-funnel',
                'color' => 'info',
                'description' => 'Ready to run',
            ],
        ];
    }
}
