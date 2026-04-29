<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use App\Models\ScreenerResult;
use App\Models\Screener;

class ScreenerResults extends Page implements Tables\Contracts\HasTable
{
    use Tables\Concerns\InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-table-cells';

    protected static string $view = 'filament.pages.screener-results';

    protected static ?string $navigationGroup = 'Screening';

    protected static ?int $navigationSort = 10;

    protected static ?string $title = 'Screener Results';

    public ?int $selectedScreener = null;

    public function mount(): void
    {
        //
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                ScreenerResult::query()
                    ->when($this->selectedScreener, fn ($q) => $q->where('screener_id', $this->selectedScreener))
                    ->latest()
            )
            ->columns([
                TextColumn::make('stock.symbol')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('stock.name')
                    ->searchable()
                    ->limit(40),

                BadgeColumn::make('screener.name')
                    ->label('Screener')
                    ->color('info'),

                TextColumn::make('match_score')
                    ->label('Match %')
                    ->numeric()
                    ->suffix('%')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->dateTime('M j, Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('screener')
                    ->options(
                        Screener::pluck('name', 'id')
                    ),
            ])
            ->actions([
                Tables\Actions\Action::make('view_stock')
                    ->icon('heroicon-o-eye')
                    ->label('View')
                    ->url(fn (ScreenerResult $record) => route('filament.admin.resources.stocks.edit', ['record' => $record->stock_id])),
            ])
            ->defaultSort('created_at', 'desc')
            ->paginated([15, 25, 50, 100]);
    }

    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }
}
