<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WatchlistResource\Pages;
use App\Models\Watchlist;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Actions\Action;

class WatchlistResource extends Resource
{
    protected static ?string $model = Watchlist::class;

    protected static ?string $navigationIcon = 'heroicon-o-eye';

    protected static ?string $navigationGroup = 'Stock Management';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Watchlist Details')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->helperText('e.g., Tech Stocks, Dividend Kings'),

                        Textarea::make('description')
                            ->rows(3)
                            ->columnSpanFull(),

                        Toggle::make('is_public')
                            ->label('Public')
                            ->helperText('Make watchlist visible to other users'),

                        Forms\Components\Select::make('user_id')
                            ->relationship('user', 'name')
                            ->required()
                            ->default(auth()->id()),
                    ])->columns(2),

                Forms\Components\Section::make('Stocks in Watchlist')
                    ->schema([
                        Forms\Components\Repeater::make('stocks')
                            ->relationship('stocks')
                            ->schema([
                                Forms\Components\Select::make('stock_id')
                                    ->relationship('stock', 'symbol')
                                    ->searchable()
                                    ->preload()
                                    ->required(),
                                Forms\Components\Textarea::make('pivot.notes')
                                    ->label('Notes')
                                    ->rows(2),
                            ])
                            ->orderColumn('position')
                            ->reorderable()
                            ->collapsible()
                            ->itemLabel(fn ($state): ?string => $state['stock']['symbol'] ?? null)
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

                TextColumn::make('user.name')
                    ->label('Owner')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('stocks_count')
                    ->label('Stocks')
                    ->counts('stocks')
                    ->badge()
                    ->color('info')
                    ->sortable(),

                IconColumn::make('is_public')
                    ->label('Public')
                    ->boolean()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('user')
                    ->relationship('user', 'name'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),

                Action::make('view_stocks')
                    ->icon('heroicon-o-eye')
                    ->label('View Stocks')
                    ->url(fn (Watchlist $record) => route('filament.admin.resources.watchlists.edit', ['record' => $record]))
                    ->color('info'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('name');
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
            'index' => Pages\ListWatchlists::route('/'),
            'create' => Pages\CreateWatchlist::route('/create'),
            'edit' => Pages\EditWatchlist::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count() ?: null;
    }
}
