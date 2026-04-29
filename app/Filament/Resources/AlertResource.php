<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AlertResource\Pages;
use App\Models\Alert;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\SelectFilter;

class AlertResource extends Resource
{
    protected static ?string $model = Alert::class;

    protected static ?string $navigationIcon = 'heroicon-o-bell';

    protected static ?string $navigationGroup = 'Alerts';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Alert Configuration')
                    ->schema([
                        Select::make('stock_id')
                            ->relationship('stock', 'symbol')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->helperText('Stock to monitor'),

                        TextInput::make('name')
                            ->label('Alert Name')
                            ->required()
                            ->maxLength(255)
                            ->helperText('e.g., AAPL RSI Oversold'),

                        Select::make('type')
                            ->options(Alert::getTypes())
                            ->required()
                            ->live(),

                        Toggle::make('is_active')
                            ->label('Active')
                            ->default(true),

                        Toggle::make('notify_telegram')
                            ->label('Telegram Notification')
                            ->default(true),

                        Toggle::make('notify_email')
                            ->label('Email Notification')
                            ->default(false),
                    ])->columns(2),

                Forms\Components\Section::make('Alert Details')
                    ->schema([
                        Textarea::make('conditions')
                            ->label('Conditions (JSON)')
                            ->helperText('Define alert conditions in JSON format')
                            ->rows(5)
                            ->columnSpanFull(),

                        TextInput::make('trigger_count')
                            ->label('Trigger Count')
                            ->disabled()
                            ->default(0),

                        TextInput::make('last_triggered_at')
                            ->label('Last Triggered')
                            ->disabled()
                            ->dateTime(),
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

                BadgeColumn::make('type')
                    ->formatStateUsing(fn ($state) => Alert::getTypes()[$state] ?? $state)
                    ->colors([
                        'price_above' => 'success',
                        'price_below' => 'danger',
                        'rsi_oversold' => 'warning',
                        'rsi_overbought' => 'danger',
                        'volume_spike' => 'info',
                        'golden_cross' => 'success',
                        'death_cross' => 'danger',
                        'macd_bullish' => 'success',
                        'macd_bearish' => 'danger',
                    ]),

                TextColumn::make('stock.symbol')
                    ->label('Stock')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->copyMessage('Symbol copied'),

                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->sortable(),

                IconColumn::make('notify_telegram')
                    ->label('Telegram')
                    ->boolean(),

                IconColumn::make('notify_email')
                    ->label('Email')
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('trigger_count')
                    ->label('Triggers')
                    ->badge()
                    ->color('info')
                    ->sortable(),

                TextColumn::make('last_triggered_at')
                    ->dateTime('M j, Y H:i')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('user.name')
                    ->label('Owner')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->options(Alert::getTypes()),
                SelectFilter::make('stock')
                    ->relationship('stock', 'symbol'),
                SelectFilter::make('user')
                    ->relationship('user', 'name'),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),

                Tables\Actions\Action::make('test')
                    ->icon('heroicon-o-play')
                    ->label('Test Alert')
                    ->color('info')
                    ->requiresConfirmation()
                    ->action(function (Alert $record) {
                        $record->logTrigger('Test alert triggered', 'telegram', true);
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListAlerts::route('/'),
            'create' => Pages\CreateAlert::route('/create'),
            'edit' => Pages\EditAlert::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('is_active', true)->count() ?: null;
    }
}
