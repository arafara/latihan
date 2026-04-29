<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StockResource\Pages;
use App\Models\Stock;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Builder;

class StockResource extends Resource
{
    protected static ?string $model = Stock::class;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?string $navigationGroup = 'Stock Management';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Stock Information')
                    ->schema([
                        TextInput::make('symbol')
                            ->label('Stock Symbol')
                            ->required()
                            ->maxLength(10)
                            ->uppercase()
                            ->unique(ignoreRecord: true)
                            ->helperText('e.g., AAPL, TSLA, MSFT'),

                        TextInput::make('name')
                            ->label('Company Name')
                            ->required()
                            ->maxLength(255)
                            ->helperText('e.g., Apple Inc.'),

                        Select::make('exchange')
                            ->options([
                                'NASDAQ' => 'NASDAQ',
                                'NYSE' => 'NYSE',
                                'AMEX' => 'AMEX',
                            ])
                            ->required()
                            ->default('NASDAQ'),

                        TextInput::make('sector')
                            ->label('Sector')
                            ->maxLength(100)
                            ->helperText('e.g., Technology, Healthcare'),

                        TextInput::make('industry')
                            ->label('Industry')
                            ->maxLength(100),

                        TextInput::make('market_cap')
                            ->label('Market Cap')
                            ->numeric()
                            ->prefix('$')
                            ->helperText('In USD'),

                        Toggle::make('is_active')
                            ->label('Active')
                            ->default(true),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('symbol')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->copyMessage('Symbol copied')
                    ->weight('bold')
                    ->color('primary'),

                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->limit(40),

                BadgeColumn::make('exchange')
                    ->colors([
                        'NASDAQ' => 'info',
                        'NYSE' => 'success',
                        'AMEX' => 'warning',
                    ]),

                TextColumn::make('sector')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('market_cap')
                    ->numeric()
                    ->sortable()
                    ->money('USD')
                    ->toggleable(isToggledHiddenByDefault: true),

                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->sortable(),

                TextColumn::make('latestPrice.close')
                    ->label('Last Price')
                    ->money('USD')
                    ->toggleable(),

                TextColumn::make('latestIndicators.rsi_14')
                    ->label('RSI')
                    ->formatStateUsing(fn ($state) => $state ? number_format($state, 2) : '-')
                    ->color(fn ($state) => $state && $state < 30 ? 'success' : ($state && $state > 70 ? 'danger' : null))
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('exchange')
                    ->options([
                        'NASDAQ' => 'NASDAQ',
                        'NYSE' => 'NYSE',
                        'AMEX' => 'AMEX',
                    ]),

                SelectFilter::make('sector')
                    ->options(fn () => Stock::distinct()->pluck('sector', 'sector')->filter()),

                SelectFilter::create('is_active')
                    ->options([
                        '1' => 'Active',
                        '0' => 'Inactive',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),

                Action::make('fetch_price')
                    ->icon('heroicon-o-arrow-path')
                    ->label('Fetch Price')
                    ->requiresConfirmation()
                    ->action(function (Stock $record) {
                        // Will be implemented with Alpaca API
                        $record->load('latestPrice');
                    })
                    ->color('info'),

                Action::make('view_chart')
                    ->icon('heroicon-o-chart-line')
                    ->label('Chart')
                    ->url(fn (Stock $record) => "https://tradingview.com/chart/?symbol={$record->exchange}:{$record->symbol}")
                    ->openUrlInNewTab()
                    ->color('success'),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('symbol')
            ->searchable()
            ->poll('60s');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStocks::route('/'),
            'create' => Pages\CreateStock::route('/create'),
            'edit' => Pages\EditStock::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count() ?: null;
    }
}
