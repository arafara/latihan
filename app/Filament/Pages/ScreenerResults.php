<?php

namespace App\Filament\Pages;

use App\Models\Screener;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Notifications\Notification;

class ScreenerResults extends Page implements Tables\Contracts\HasTable
{
    use Tables\Concerns\InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-table-cells';

    protected static string $view = 'filament.pages.screener-results';

    protected static ?string $navigationGroup = 'Screening';

    protected static ?int $navigationSort = 2;

    protected static ?string $title = 'Screener Results';

    public ?Screener $screener = null;

    public function mount(int $record): void
    {
        $this->screener = Screener::with(['results.stock'])->findOrFail($record);
        
        // Run the screener if not run yet
        if (!$this->screener->last_run_at) {
            $this->screener->run();
            
            Notification::make()
                ->title('Screener executed')
                ->body("Found {$this->screener->last_result_count} matching stocks.")
                ->success()
                ->send();
        }
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                \App\Models\Stock::query()
                    ->with(['latestIndicators', 'latestPrice'])
            )
            ->columns([
                TextColumn::make('symbol')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->copyable()
                    ->copyMessage('Copied!'),

                TextColumn::make('name')
                    ->searchable()
                    ->limit(30),

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
                    ->color(fn ($state) => $state && $state > 0 ? 'success' : ($state && $state < 0 ? 'danger' : null)),

                TextColumn::make('sector')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('exchange')
                    ->options([
                        'NASDAQ' => 'NASDAQ',
                        'NYSE' => 'NYSE',
                        'AMEX' => 'AMEX',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('view_chart')
                    ->icon('heroicon-o-chart-line')
                    ->label('Chart')
                    ->url(fn ($record) => "https://tradingview.com/chart/?symbol={$record->exchange}:{$record->symbol}")
                    ->openUrlInNewTab()
                    ->color('info'),
            ])
            ->defaultSort('symbol');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return false; // Hidden from nav, accessed via action
    }
}
