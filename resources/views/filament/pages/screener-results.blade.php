<x-filament-panels::page>
    <x-filament::section>
        <div class="flex items-center gap-x-2.5 mb-6">
            <div class="flex-1">
                <h3 class="text-base font-semibold leading-6 text-gray-950 dark:text-white">
                    Screener Results
                </h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    View all stocks matching your screening criteria
                </p>
            </div>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Filter by Screener:
            </label>
            <select
                wire:model.live="selectedScreener"
                class="block w-full rounded-md border-gray-300 dark:border-gray-700 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-gray-900 dark:text-white"
            >
                <option value="">All Screeners</option>
                @foreach (\App\Models\Screener::all() as $screener)
                <option value="{{ $screener->id }}">
                    {{ $screener->name }}
                </option>
                @endforeach
            </select>
        </div>

        {{ $this->table }}

        <div class="mt-6 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
            <h4 class="text-sm font-medium text-gray-800 dark:text-gray-200 mb-2">
                About Screener Results
            </h4>
            <p class="text-sm text-gray-600 dark:text-gray-400">
                Results are automatically generated when you run a screener.
                Use the filter above to view results from a specific screener,
                or view all results across all screeners.
            </p>
        </div>
    </x-filament::section>
</x-filament-panels::page>
