<?php

namespace App\Console\Commands;

use App\Models\Stock;
use App\Services\Alpaca\AlpacaService;
use App\Services\Finnhub\FinnhubService;
use Illuminate\Console\Command;

class ImportWatchlistStocks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stocks:import 
                            {symbols?* : Stock symbols to import} 
                            {--all : Import all from watchlist file}
                            {--skip-historical : Skip historical data fetch}
                            {--skip-indicators : Skip indicator calculation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import stocks from watchlist (Alpaca + Finnhub API)';

    protected AlpacaService $alpaca;
    protected FinnhubService $finnhub;

    public function handle(AlpacaService $alpaca, FinnhubService $finnhub): int
    {
        $this->alpaca = $alpaca;
        $this->finnhub = $finnhub;

        $symbols = $this->argument('symbols');

        if ($this->option('all')) {
            $symbols = $this->loadWatchlistFile();
        }

        if (empty($symbols)) {
            $this->error('No symbols provided. Usage: php artisan stocks:import AAPL TSLA MSFT');
            return Command::FAILURE;
        }

        $this->info("Importing " . count($symbols) . " stocks...");

        $bar = $this->output->createProgressBar(count($symbols));
        $bar->start();

        $success = 0;
        $failed = 0;

        foreach ($symbols as $symbol) {
            $symbol = strtoupper(trim($symbol));

            try {
                $this->importStock($symbol);
                $success++;
            } catch (\Exception $e) {
                $this->error("\nFailed to import {$symbol}: " . $e->getMessage());
                $failed++;
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();

        $this->info("✅ Import completed!");
        $this->table(
            ['Status', 'Count'],
            [
                ['Success', $success],
                ['Failed', $failed],
                ['Total', count($symbols)],
            ]
        );

        return Command::SUCCESS;
    }

    /**
     * Import a single stock.
     */
    protected function importStock(string $symbol): void
    {
        // Get company profile from Finnhub
        $profile = $this->finnhub->getCompanyProfile($symbol);

        // Get snapshot from Alpaca (includes latest price)
        $snapshot = $this->alpaca->getSnapshot($symbol);

        Stock::updateOrCreate(
            ['symbol' => $symbol],
            [
                'name' => $profile['name'] ?? ($snapshot['name'] ?? $symbol),
                'exchange' => $profile['exchange'] ?? 'NASDAQ',
                'sector' => $profile['finnhubIndustry'] ?? null,
                'industry' => $profile['industry'] ?? null,
                'market_cap' => $profile['marketCapitalization'] ?? null,
                'is_active' => true,
            ]
        );

        // Fetch historical data for indicators
        $this->fetchHistoricalData($symbol);
    }

    /**
     * Fetch historical price data and calculate indicators.
     */
    protected function fetchHistoricalData(string $symbol): void
    {
        if ($this->option('skip-historical')) {
            $this->warn("Skipping historical data for {$symbol}");
            return;
        }

        $stock = Stock::where('symbol', $symbol)->first();

        if (!$stock) {
            throw new \Exception("Stock {$symbol} not found in database");
        }

        try {
            // Fetch 200 days of historical data
            $bars = $this->alpaca->getBars(
                $symbol,
                '1Day',
                now()->subDays(250),
                now(),
                200
            );

            if ($bars->isEmpty()) {
                $this->warn("No historical data for {$symbol} (API limitation)");
                return;
            }

            // Save price data
            foreach ($bars as $bar) {
                \App\Models\StockPrice::updateOrCreate(
                    [
                        'stock_id' => $stock->id,
                        'date' => $bar['timestamp']->format('Y-m-d'),
                    ],
                    [
                        'open' => $bar['open'],
                        'high' => $bar['high'],
                        'low' => $bar['low'],
                        'close' => $bar['close'],
                        'volume' => $bar['volume'],
                        'vwap' => $bar['vwap'] ?? null,
                        'trade_count' => $bar['trade_count'] ?? null,
                    ]
                );
            }

            // Calculate technical indicators
            if (!$this->option('skip-indicators')) {
                $indicators = \App\Services\Indicators\TechnicalIndicatorCalculator::calculateAll($bars);

                \App\Models\TechnicalIndicator::updateOrCreate(
                    [
                        'stock_id' => $stock->id,
                        'date' => now()->format('Y-m-d'),
                    ],
                    $indicators
                );
            }

        } catch (\Exception $e) {
            $this->warn("Failed to fetch historical data for {$symbol}: " . $e->getMessage());
            // Continue without throwing - stock is still imported
        }
    }

    /**
     * Load symbols from watchlist file.
     */
    protected function loadWatchlistFile(): array
    {
        $filePath = storage_path('app/watchlist.txt');

        if (!file_exists($filePath)) {
            $this->error("Watchlist file not found at: {$filePath}");
            $this->info("Create a file with one stock symbol per line, e.g.:");
            $this->info("AAPL");
            $this->info("TSLA");
            $this->info("MSFT");
            return [];
        }

        $symbols = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        return array_map('strtoupper', $symbols);
    }
}
