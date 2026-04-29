<x-filament-panels::page>
    <div class="space-y-6">
        <x-filament::section>
            <x-slot name="heading">
                {{ $this->screener->name }}
            </x-slot>

            <x-slot name="description">
                {{ $this->screener->description ?? 'No description' }}
            </x-slot>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="text-center p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                    <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">
                        {{ $this->screener->last_result_count ?? 0 }}
                    </div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">
                        Matching Stocks
                    </div>
                </div>

                <div class="text-center p-4 bg-green-50 dark:bg-green-900/20 rounded-lg">
                    <div class="text-2xl font-bold text-green-600 dark:text-green-400">
                        {{ count($this->screener->filters ?? []) }}
                    </div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">
                        Filters
                    </div>
                </div>

                <div class="text-center p-4 bg-purple-50 dark:bg-purple-900/20 rounded-lg">
                    <div class="text-2xl font-bold text-purple-600 dark:text-purple-400">
                        {{ $this->screener->last_run_at?->diffForHumans() ?? 'Never' }}
                    </div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">
                        Last Run
                    </div>
                </div>

                <div class="text-center p-4 bg-orange-50 dark:bg-orange-900/20 rounded-lg">
                    <div class="text-2xl font-bold text-orange-600 dark:text-orange-400">
                        {{ $this->screener->is_active ? 'Active' : 'Inactive' }}
                    </div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">
                        Status
                    </div>
                </div>
            </div>
        </x-filament::section>

        <x-filament::section>
            <x-slot name="heading">
                Screening Results
            </x-slot>

            {{ $this->table }}
        </x-filament::section>
    </div>
</x-filament-panels::page>
