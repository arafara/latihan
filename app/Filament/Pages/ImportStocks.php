<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Checkbox;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Artisan;

class ImportStocks extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-down-tray';

    protected static string $view = 'filament.pages.import-stocks';

    protected static ?string $navigationGroup = 'Stock Management';

    protected static ?int $navigationSort = 3;

    protected static ?string $title = 'Import Stocks';

    public ?string $symbols = '';

    public bool $fetchHistorical = true;

    public function mount(): void
    {
        // Pre-populate with example symbols
        $this->symbols = "AAPL\nTSLA\nMSFT\nGOOGL\nAMZN";
    }

    public function import(): void
    {
        $symbols = array_filter(
            array_map('trim', explode("\n", $this->symbols)),
            fn($s) => !empty($s)
        );

        if (empty($symbols)) {
            Notification::make()
                ->title('No symbols provided')
                ->body('Please enter at least one stock symbol.')
                ->danger()
                ->send();
            return;
        }

        $symbolList = implode(' ', array_map('strtoupper', $symbols));

        try {
            // Run the import command
            Artisan::call('stocks:import', [
                'symbols' => $symbols,
            ]);

            $output = Artisan::output();

            Notification::make()
                ->title('Import completed!')
                ->body(count($symbols) . ' stocks imported successfully.')
                ->success()
                ->send();

            // Redirect to stocks list
            redirect(route('filament.admin.resources.stocks.index'));

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
