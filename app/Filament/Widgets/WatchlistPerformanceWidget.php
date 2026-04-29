<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use App\Models\Watchlist;
use App\Models\Stock;
use Illuminate\Support\Facades\DB;

class WatchlistPerformanceWidget extends Widget
{
    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 'full';

    protected static string $view = 'filament.widgets.watchlist-performance-widget';

    public function getWatchlists(): array
    {
        return Watchlist::withCount('stocks')
            ->orderBy('stocks_count', 'desc')
            ->limit(5)
            ->get()
            ->map(fn ($watchlist) => [
                'name' => $watchlist->name,
                'stocks_count' => $watchlist->stocks_count,
                'description' => $watchlist->description ?? 'No description',
            ])
            ->toArray();
    }

    public function getTopStocks(): array
    {
        // Get most watched stocks
        return Stock::select('symbol', 'name', 'exchange')
            ->where('is_active', true)
            ->limit(10)
            ->get()
            ->map(fn ($stock) => [
                'symbol' => $stock->symbol,
                'name' => $stock->name ?? $stock->symbol,
                'exchange' => $stock->exchange,
            ])
            ->toArray();
    }
}
