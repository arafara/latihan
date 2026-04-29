<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Checkbox;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Artisan;

class ImportStocks extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cloud-arrow-up';

    protected static string $view = 'filament.pages.import-stocks';

    protected static ?string $navigationGroup = 'Stock Management';

    protected static ?int $navigationSort = 10;

    protected static ?string $title = 'Import Stocks';

    public ?string $symbols = '';

    public bool $fetchHistorical = false;

    public bool $fetchIndicators = false;

    public function mount(): void
    {
        // Pre-populate with example symbols
        $this->symbols = "AAPL\nTSLA\nMSFT\nGOOGL\nAMZN";
    }

    public function import(): void
    {
        $symbols = array_filter(array_map('trim', explode("\n", $this->symbols)));

        if (empty($symbols)) {
            Notification::make()
                ->title('No symbols provided')
                ->danger()
                ->send();
            return;
        }

        // Store symbols in session for import command
        session(['import_symbols' => $symbols]);

        // Run import command
        try {
            Artisan::call('stocks:import', [
                '--symbols' => implode(',', $symbols),
                '--fetch-historical' => $this->fetchHistorical,
                '--fetch-indicators' => $this->fetchIndicators,
            ]);

            $output = Artisan::output();

            Notification::make()
                ->title('Import completed')
                ->body('Successfully imported ' . count($symbols) . ' stocks')
                ->success()
                ->send();

        } catch (\Exception $e) {
            Notification::make()
                ->title('Import failed')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }
}
