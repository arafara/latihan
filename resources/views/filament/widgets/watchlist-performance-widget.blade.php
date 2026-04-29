<x-filament-widgets::widget>
    <x-filament::section>
        <div class="flex items-center gap-x-2.5">
            <div class="flex-1">
                <h3 class="text-base font-semibold leading-6 text-gray-950 dark:text-white">
                    Watchlist Performance
                </h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Top watchlists and stocks
                </p>
            </div>
        </div>

        <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-2">
            {{-- Top Watchlists --}}
            <div>
                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-3">
                    Top Watchlists
                </h4>
                <div class="space-y-3">
                    @foreach ($this->getWatchlists() as $watchlist)
                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                        <div>
                            <p class="font-medium text-gray-950 dark:text-white">
                                {{ $watchlist['name'] }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                {{ Str::limit($watchlist['description'], 50) }}
                            </p>
                        </div>
                        <div class="text-right">
                            <span class="inline-flex items-center rounded-md bg-primary-50 dark:bg-primary-400/10 px-2 py-1 text-xs font-medium text-primary-700 dark:text-primary-400 ring-1 ring-inset ring-primary-700/10">
                                {{ $watchlist['stocks_count'] }} stocks
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Top Stocks --}}
            <div>
                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-3">
                    Most Tracked Stocks
                </h4>
                <div class="space-y-2">
                    @foreach ($this->getTopStocks() as $stock)
                    <div class="flex items-center justify-between p-2 hover:bg-gray-50 dark:hover:bg-gray-800 rounded">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-primary-100 dark:bg-primary-900 flex items-center justify-center">
                                <span class="text-xs font-bold text-primary-700 dark:text-primary-300">
                                    {{ substr($stock['symbol'], 0, 2) }}
                                </span>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-950 dark:text-white">
                                    {{ $stock['symbol'] }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $stock['name'] }}
                                </p>
                            </div>
                        </div>
                        <span class="text-xs text-gray-500 dark:text-gray-400">
                            {{ $stock['exchange'] }}
                        </span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
