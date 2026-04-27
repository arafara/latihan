<?php

namespace App\Services\Alpaca;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class AlpacaService
{
    protected string $apiKey;
    protected string $apiSecret;
    protected string $baseUrl;
    protected int $timeout;

    public function __construct()
    {
        $this->apiKey = config('services.alpaca.key');
        $this->apiSecret = config('services.alpaca.secret');
        $this->baseUrl = config('services.alpaca.base_url', 'https://data.alpaca.markets');
        $this->timeout = config('services.alpaca.timeout', 30);
    }

    /**
     * Get HTTP client with authentication.
     */
    protected function client(): \GuzzleHttp\Client
    {
        return Http::withHeaders([
            'APCA-API-KEY-ID' => $this->apiKey,
            'APCA-API-SECRET-KEY' => $this->apiSecret,
            'Accept' => 'application/json',
        ])->timeout($this->timeout)->asJson()->client();
    }

    /**
     * Get latest quote for a stock.
     */
    public function getQuote(string $symbol): ?array
    {
        try {
            $response = Http::withHeaders([
                'APCA-API-KEY-ID' => $this->apiKey,
                'APCA-API-SECRET-KEY' => $this->apiSecret,
            ])->get("{$this->baseUrl}/v2/stocks/{$symbol}/quotes/latest");

            if ($response->successful()) {
                return $response->json('quote');
            }

            return null;
        } catch (\Exception $e) {
            logger()->error('Alpaca API Error (Quote): ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get latest trades for a stock.
     */
    public function getLatestTrade(string $symbol): ?array
    {
        try {
            $response = Http::withHeaders([
                'APCA-API-KEY-ID' => $this->apiKey,
                'APCA-API-SECRET-KEY' => $this->apiSecret,
            ])->get("{$this->baseUrl}/v2/stocks/{$symbol}/trades/latest");

            if ($response->successful()) {
                return $response->json('trade');
            }

            return null;
        } catch (\Exception $e) {
            logger()->error('Alpaca API Error (Trade): ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get OHLCV bars for a stock.
     * 
     * @param string $symbol Stock symbol
     * @param string $timeframe Timeframe (1Min, 5Min, 15Min, 1Hour, 1Day)
     * @param Carbon|null $start Start date
     * @param Carbon|null $end End date
     * @param int $limit Maximum number of bars (max 10000)
     */
    public function getBars(
        string $symbol,
        string $timeframe = '1Day',
        ?Carbon $start = null,
        ?Carbon $end = null,
        int $limit = 1000
    ): Collection {
        try {
            $params = [
                'timeframe' => $timeframe,
                'limit' => min($limit, 10000),
            ];

            if ($start) {
                $params['start'] = $start->format('Y-m-d');
            }

            if ($end) {
                $params['end'] = $end->format('Y-m-d');
            }

            $response = Http::withHeaders([
                'APCA-API-KEY-ID' => $this->apiKey,
                'APCA-API-SECRET-KEY' => $this->apiSecret,
            ])->get("{$this->baseUrl}/v2/stocks/{$symbol}/bars", $params);

            if ($response->successful()) {
                $bars = $response->json('bars', []);
                return collect($bars)->map(function ($bar) {
                    return [
                        'timestamp' => Carbon::parse($bar['t']),
                        'open' => $bar['o'],
                        'high' => $bar['h'],
                        'low' => $bar['l'],
                        'close' => $bar['c'],
                        'volume' => $bar['v'],
                        'trade_count' => $bar['t'] ?? null,
                        'vwap' => $bar['vwap'] ?? null,
                    ];
                });
            }

            return collect();
        } catch (\Exception $e) {
            logger()->error('Alpaca API Error (Bars): ' . $e->getMessage());
            return collect();
        }
    }

    /**
     * Get multiple stocks bars in one request.
     */
    public function getMultipleBars(
        array $symbols,
        string $timeframe = '1Day',
        ?Carbon $start = null,
        ?Carbon $end = null,
        int $limit = 1000
    ): array {
        try {
            $params = [
                'timeframe' => $timeframe,
                'limit' => min($limit, 10000),
                'symbols' => implode(',', $symbols),
            ];

            if ($start) {
                $params['start'] = $start->format('Y-m-d');
            }

            if ($end) {
                $params['end'] = $end->format('Y-m-d');
            }

            $response = Http::withHeaders([
                'APCA-API-KEY-ID' => $this->apiKey,
                'APCA-API-SECRET-KEY' => $this->apiSecret,
            ])->get("{$this->baseUrl}/v2/stocks/multi/bars", $params);

            if ($response->successful()) {
                return $response->json('bars', []);
            }

            return [];
        } catch (\Exception $e) {
            logger()->error('Alpaca API Error (Multi Bars): ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get stock snapshot (latest quote, trade, minute bar, daily bar, and previous daily bar).
     */
    public function getSnapshot(string $symbol): ?array
    {
        try {
            $response = Http::withHeaders([
                'APCA-API-KEY-ID' => $this->apiKey,
                'APCA-API-SECRET-KEY' => $this->apiSecret,
            ])->get("{$this->baseUrl}/v2/stocks/{$symbol}/snapshot");

            if ($response->successful()) {
                return $response->json();
            }

            return null;
        } catch (\Exception $e) {
            logger()->error('Alpaca API Error (Snapshot): ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get snapshots for multiple stocks.
     */
    public function getMultipleSnapshots(array $symbols): array
    {
        try {
            $params = ['symbols' => implode(',', $symbols)];

            $response = Http::withHeaders([
                'APCA-API-KEY-ID' => $this->apiKey,
                'APCA-API-SECRET-KEY' => $this->apiSecret,
            ])->get("{$this->baseUrl}/v2/stocks/multi/snapshot", $params);

            if ($response->successful()) {
                return $response->json();
            }

            return [];
        } catch (\Exception $e) {
            logger()->error('Alpaca API Error (Multi Snapshot): ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get market status.
     */
    public function getMarketStatus(): ?array
    {
        try {
            $response = Http::withHeaders([
                'APCA-API-KEY-ID' => $this->apiKey,
                'APCA-API-SECRET-KEY' => $this->apiSecret,
            ])->get("{$this->baseUrl}/v2/markets/status");

            if ($response->successful()) {
                return $response->json();
            }

            return null;
        } catch (\Exception $e) {
            logger()->error('Alpaca API Error (Market Status): ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get market calendar.
     */
    public function getMarketCalendar(?Carbon $start = null, ?Carbon $end = null): array
    {
        try {
            $params = [];

            if ($start) {
                $params['start'] = $start->format('Y-m-d');
            }

            if ($end) {
                $params['end'] = $end->format('Y-m-d');
            }

            $response = Http::withHeaders([
                'APCA-API-KEY-ID' => $this->apiKey,
                'APCA-API-SECRET-KEY' => $this->apiSecret,
            ])->get("{$this->baseUrl}/v2/marketdata/calendar", $params);

            if ($response->successful()) {
                return $response->json('calendar', []);
            }

            return [];
        } catch (\Exception $e) {
            logger()->error('Alpaca API Error (Calendar): ' . $e->getMessage());
            return [];
        }
    }
}
