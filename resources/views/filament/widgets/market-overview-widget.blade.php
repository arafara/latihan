<x-filament-widgets::widget>
    <x-filament::section>
        <div class="flex items-center gap-x-2.5">
            <div class="flex-1">
                <h3 class="text-base font-semibold leading-6 text-gray-950 dark:text-white">
                    Market Overview
                </h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Quick stats of your stock screener
                </p>
            </div>
        </div>

        <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($this->getStats() as $stat)
            <div class="relative overflow-hidden rounded-lg bg-white dark:bg-gray-900 p-6 shadow ring-1 ring-gray-950/5">
                <dt>
                    <div class="absolute rounded-md bg-{{ $stat['color'] }}-500 p-3">
                        <x-dynamic-component
                            :component="$stat['icon']"
                            class="h-6 w-6 text-white"
                            aria-hidden="true"
                        />
                    </div>
                    <p class="ml-16 truncate text-sm font-medium text-gray-500 dark:text-gray-400">
                        {{ $stat['label'] }}
                    </p>
                </dt>
                <dd class="ml-16 flex items-baseline pb-1 sm:pb-2">
                    <p class="text-2xl font-semibold text-gray-950 dark:text-white">
                        {{ $stat['value'] }}
                    </p>
                    <p class="ml-2 flex items-baseline text-sm font-semibold text-{{ $stat['color'] }}-600 dark:text-{{ $stat['color'] }}-400">
                        <span class="text-xs text-gray-500 dark:text-gray-400 font-normal">
                            {{ $stat['description'] }}
                        </span>
                    </p>
                </dd>
            </div>
            @endforeach
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
