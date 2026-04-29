<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ScreenerResource\Pages;
use App\Models\Screener;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Actions\Action;

class ScreenerResource extends Resource
{
    protected static ?string $model = Screener::class;

    protected static ?string $navigationIcon = 'heroicon-o-funnel';

    protected static ?string $navigationGroup = 'Screening';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Screener Details')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->helperText('e.g., Oversold Stocks, Golden Cross'),

                        Textarea::make('description')
                            ->rows(3)
                            ->columnSpanFull(),

                        Toggle::make('is_public')
                            ->label('Public')
                            ->default(false),

                        Toggle::make('is_active')
                            ->label('Active')
                            ->default(true),
                    ])->columns(2),

                Forms\Components\Section::make('Screening Filters')
                    ->description('Define your screening criteria')
                    ->schema([
                        Repeater::make('filters')
                            ->schema([
                                Select::make('category')
                                    ->options([
                                        'trend' => 'Trend',
                                        'momentum' => 'Momentum',
                                        'volume' => 'Volume',
                                        'volatility' => 'Volatility',
                                    ])
                                    ->required()
                                    ->live(),

                                Select::make('indicator')
                                    ->options(fn (callable $get) => self::getIndicatorsByCategory($get('category')))
                                    ->required(),

                                Select::make('operator')
                                    ->options([
                                        '>' => 'Greater than',
                                        '>=' => 'Greater or equal',
                                        '<' => 'Less than',
                                        '<=' => 'Less or equal',
                                        '=' => 'Equals',
                                        '!=' => 'Not equals',
                                    ])
                                    ->required(),

                                TextInput::make('value')
                                    ->numeric()
                                    ->required(),
                            ])
                            ->columns(4)
                            ->collapsible()
                            ->itemLabel(fn ($state): ?string => $state['indicator'] ?? null)
                            ->minItems(1)
                            ->maxItems(10)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('description')
                    ->limit(50)
                    ->toggleable(isToggledHiddenByDefault: true),

                BadgeColumn::make('filter_count')
                    ->label('Filters')
                    ->formatStateUsing(fn ($record) => count($record->filters ?? []))
                    ->color('info'),

                TextColumn::make('last_result_count')
                    ->label('Last Results')
                    ->badge()
                    ->color('success')
                    ->sortable(),

                TextColumn::make('last_run_at')
                    ->dateTime('M j, Y H:i')
                    ->sortable()
                    ->toggleable(),

                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->sortable(),

                IconColumn::make('is_public')
                    ->label('Public')
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('user.name')
                    ->label('Owner')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('user')
                    ->relationship('user', 'name'),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),

                Action::make('run')
                    ->icon('heroicon-o-play')
                    ->label('Run Screener')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function (Screener $record) {
                        // Run the screener
                        $record->run();
                    }),

                Action::make('view_results')
                    ->icon('heroicon-o-table-cells')
                    ->label('View Results')
                    ->url(fn (Screener $record) => route('filament.admin.resources.screener-results', ['record' => $record]))
                    ->openUrlInNewTab()
                    ->color('info'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('name');
    }

    public static function getIndicatorsByCategory(?string $category): array
    {
        return match ($category) {
            'trend' => [
                'sma_20' => 'SMA 20',
                'sma_50' => 'SMA 50',
                'sma_200' => 'SMA 200',
                'ema_12' => 'EMA 12',
                'ema_26' => 'EMA 26',
            ],
            'momentum' => [
                'rsi_14' => 'RSI (14)',
                'macd' => 'MACD',
                'stochastic_k' => 'Stochastic %K',
                'stochastic_d' => 'Stochastic %D',
            ],
            'volume' => [
                'volume_sma_20' => 'Volume SMA 20',
                'obv' => 'OBV',
            ],
            'volatility' => [
                'bollinger_upper' => 'Bollinger Upper',
                'bollinger_lower' => 'Bollinger Lower',
                'atr_14' => 'ATR (14)',
            ],
            default => [],
        };
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
            'index' => Pages\ListScreeners::route('/'),
            'create' => Pages\CreateScreener::route('/create'),
            'edit' => Pages\EditScreener::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('is_active', true)->count() ?: null;
    }
}
