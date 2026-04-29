<?php

namespace App\Filament\Widgets;

use App\Models\Stock;
use App\Models\TechnicalIndicator;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Illuminate\Database\Eloquent\Builder;

class WatchlistPerformanceWidget extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 2;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Stock::query()
                    ->with(['latestIndicators'])
                    ->whereHas('watchlists')
                    ->orderBy('symbol')
                    ->limit(10)
            )
            ->defaultPaginationPageOption(10)
            ->columns([
                TextColumn::make('symbol')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->copyable()
                    ->copyMessage('Copied!'),

                TextColumn::make('name')
                    ->limit(30)
                    ->toggleable(),

                BadgeColumn::make('exchange')
                    ->colors([
                        'NASDAQ' => 'info',
                        'NYSE' => 'success',
                        'AMEX' => 'warning',
                    ]),

                TextColumn::make('latestIndicators.rsi_14')
                    ->label('RSI')
                    ->formatStateUsing(fn ($state) => $state ? number_format($state, 2) : '-')
                    ->color(fn ($state) => $state && $state < 30 ? 'success' : ($state && $state > 70 ? 'danger' : null))
                    ->sortable(),

                TextColumn::make('latestIndicators.change_percent')
                    ->label('Change %')
                    ->formatStateUsing(fn ($state) => $state ? number_format($state, 2) . '%' : '-')
                    ->color(fn ($state) => $state && $state > 0 ? 'success' : ($state && $state < 0 ? 'danger' : null))
                    ->sortable(),

                TextColumn::make('sector')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([
                Tables\Actions\Action::make('view_chart')
                    ->icon('heroicon-o-chart-line')
                    ->label('Chart')
                    ->url(fn (Stock $record) => "https://tradingview.com/chart/?symbol={$record->exchange}:{$record->symbol}")
                    ->openUrlInNewTab()
                    ->color('info'),
            ])
            ->poll('60s');
    }
}
