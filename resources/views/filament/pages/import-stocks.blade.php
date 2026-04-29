<x-filament-panels::page>
    <x-filament::section>
        <x-slot name="heading">
            Import Stocks from Watchlist
        </x-slot>

        <x-slot name="description">
            Enter stock symbols (one per line) or upload a file to import multiple stocks at once.
        </x-slot>

        <form wire:submit="import" class="space-y-6">
            {{ $this->form }}

            <div class="flex items-center gap-4">
                <x-filament::button type="submit" color="primary">
                    <x-heroicon-o-arrow-down-tray class="w-5 h-5 mr-2" />
                    Import Stocks
                </x-filament::button>

                <x-filament::button type="button" color="gray" tag="a" href="{{ route('filament.admin.resources.stocks.index') }}">
                    Cancel
                </x-filament::button>
            </div>
        </form>
    </x-filament::section>

    <x-filament::section class="mt-6">
        <x-slot name="heading">
            Instructions
        </x-slot>

        <div class="prose dark:prose-invert">
            <ol>
                <li>Enter stock symbols, one per line (e.g., AAPL, TSLA, MSFT)</li>
                <li>Click "Import Stocks" to start the import process</li>
                <li>The system will fetch company data from Finnhub and price data from Alpaca</li>
                <li>Technical indicators will be calculated automatically</li>
            </ol>

            <div class="mt-4 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                <p class="text-sm text-blue-800 dark:text-blue-200">
                    <strong>Note:</strong> Import may take 2-5 seconds per stock. For 200 stocks, expect 10-15 minutes.
                </p>
            </div>
        </div>
    </x-filament::section>
</x-filament-panels::page>
