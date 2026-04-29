<x-filament-panels::page>
    <x-filament::section>
        <div class="flex items-center gap-x-2.5 mb-6">
            <div class="flex-1">
                <h3 class="text-base font-semibold leading-6 text-gray-950 dark:text-white">
                    Import Stocks from Watchlist
                </h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Add multiple stocks by symbol (one per line)
                </p>
            </div>
        </div>

        <form wire:submit="import" class="space-y-6">
            {{ $this->form }}

            <div class="flex items-center gap-4">
                <x-filament::button type="submit" color="primary">
                    <x-heroicon-o-cloud-arrow-up class="w-5 h-5 mr-2" />
                    Import Stocks
                </x-filament::button>

                <x-filament::button type="button" color="gray" tag="a" href="{{ route('filament.admin.resources.stocks.index') }}">
                    View All Stocks
                </x-filament::button>
            </div>
        </form>

        <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
            <h4 class="text-sm font-medium text-blue-800 dark:text-blue-300 mb-2">
                Tips:
            </h4>
            <ul class="text-sm text-blue-700 dark:text-blue-400 space-y-1 list-disc list-inside">
                <li>Enter one stock symbol per line (e.g., AAPL, TSLA, MSFT)</li>
                <li>Enable "Fetch Historical Data" to download price history (slower)</li>
                <li>Enable "Fetch Indicators" to calculate technical indicators (RSI, MACD, etc.)</li>
                <li>API rate limits may apply - import in batches if needed</li>
            </ul>
        </div>
    </x-filament::section>
</x-filament-panels::page>
