<?php

namespace App\Services\Finnhub;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Collection;

class FinnhubService
{
    protected string $apiKey;
    protected string $baseUrl;
    protected int $timeout;

    public function __construct()
    {
        $this->apiKey = config('services.finnhub.key');
        $this->baseUrl = config('services.finnhub.base_url', 'https://finnhub.io/api/v1');
        $this->timeout = config('services.finnhub.timeout', 30);
    }

    /**
     * Get real-time quote.
     */
    public function getQuote(string $symbol): ?array
    {
        try {
            $response = Http::get("{$this->baseUrl}/quote", [
                'symbol' => $symbol,
                'token' => $this->apiKey,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                if ($data && isset($data['c'])) {
                    return [
                        'current' => $data['c'],
                        'high' => $data['h'],
                        'low' => $data['l'],
                        'open' => $data['o'],
                        'previous_close' => $data['pc'],
                        'change' => $data['c'] - $data['pc'],
                        'change_percent' => $data['dp'],
                        'timestamp' => $data['t'],
                    ];
                }
            }

            return null;
        } catch (\Exception $e) {
            logger()->error('Finnhub API Error (Quote): ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get company profile II (detailed company info).
     */
    public function getCompanyProfile(string $symbol): ?array
    {
        try {
            $response = Http::get("{$this->baseUrl}/stock/profile2", [
                'symbol' => $symbol,
                'token' => $this->apiKey,
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            return null;
        } catch (\Exception $e) {
            logger()->error('Finnhub API Error (Profile): ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get company fundamental data (P/E, P/B, ROE, etc.).
     */
    public function getCompanyMetrics(string $symbol): ?array
    {
        try {
            $response = Http::get("{$this->baseUrl}/stock/metric", [
                'symbol' => $symbol,
                'metric' => 'all',
                'token' => $this->apiKey,
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            return null;
        } catch (\Exception $e) {
            logger()->error('Finnhub API Error (Metrics): ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get stock candles (OHLCV).
     */
    public function getCandles(
        string $symbol,
        int $resolution,
        int $from,
        int $to
    ): ?array {
        try {
            $response = Http::get("{$this->baseUrl}/stock/candle", [
                'symbol' => $symbol,
                'resolution' => $resolution, // 1, 5, 15, 30, 60, D, W, M
                'from' => $from, // Unix timestamp
                'to' => $to,
                'token' => $this->apiKey,
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            return null;
        } catch (\Exception $e) {
            logger()->error('Finnhub API Error (Candles): ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get stock screener results.
     */
    public function screener(array $filters = []): Collection
    {
        try {
            $params = [
                'token' => $this->apiKey,
            ];

            // Add filters
            if (isset($filters['market_cap_min'])) {
                $params['market_cap_min'] = $filters['market_cap_min'];
            }
            if (isset($filters['market_cap_max'])) {
                $params['market_cap_max'] = $filters['market_cap_max'];
            }
            if (isset($filters['pe_ratio_min'])) {
                $params['pe_ratio_min'] = $filters['pe_ratio_min'];
            }
            if (isset($filters['pe_ratio_max'])) {
                $params['pe_ratio_max'] = $filters['pe_ratio_max'];
            }
            if (isset($filters['sector'])) {
                $params['sector'] = $filters['sector'];
            }
            if (isset($filters['exchange'])) {
                $params['exchange'] = $filters['exchange'];
            }
            if (isset($filters['country'])) {
                $params['country'] = $filters['country'];
            }

            $response = Http::get("{$this->baseUrl}/stock/screener", $params);

            if ($response->successful()) {
                return collect($response->json('result', []));
            }

            return collect();
        } catch (\Exception $e) {
            logger()->error('Finnhub API Error (Screener): ' . $e->getMessage());
            return collect();
        }
    }

    /**
     * Get company news.
     */
    public function getCompanyNews(
        string $symbol,
        string $from,
        string $to
    ): Collection {
        try {
            $response = Http::get("{$this->baseUrl}/company-news", [
                'symbol' => $symbol,
                'from' => $from,
                'to' => $to,
                'token' => $this->apiKey,
            ]);

            if ($response->successful()) {
                return collect($response->json());
            }

            return collect();
        } catch (\Exception $e) {
            logger()->error('Finnhub API Error (News): ' . $e->getMessage());
            return collect();
        }
    }

    /**
     * Get earnings estimates.
     */
    public function getEarningsEstimates(string $symbol): ?array
    {
        try {
            $response = Http::get("{$this->baseUrl}/stock/earnings-estimate", [
                'symbol' => $symbol,
                'token' => $this->apiKey,
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            return null;
        } catch (\Exception $e) {
            logger()->error('Finnhub API Error (Earnings): ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get price targets.
     */
    public function getPriceTargets(string $symbol): ?array
    {
        try {
            $response = Http::get("{$this->baseUrl}/stock/price-target", [
                'symbol' => $symbol,
                'token' => $this->apiKey,
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            return null;
        } catch (\Exception $e) {
            logger()->error('Finnhub API Error (Price Targets): ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get recommendation trends.
     */
    public function getRecommendations(string $symbol): Collection
    {
        try {
            $response = Http::get("{$this->baseUrl}/stock/recommendation", [
                'symbol' => $symbol,
                'token' => $this->apiKey,
            ]);

            if ($response->successful()) {
                return collect($response->json());
            }

            return collect();
        } catch (\Exception $e) {
            logger()->error('Finnhub API Error (Recommendations): ' . $e->getMessage());
            return collect();
        }
    }

    /**
     * Get US holidays (market closed days).
     */
    public function getMarketHolidays(string $year): Collection
    {
        try {
            $response = Http::get("{$this->baseUrl}/stock/market-holiday", [
                'year' => $year,
                'token' => $this->apiKey,
            ]);

            if ($response->successful()) {
                return collect($response->json());
            }

            return collect();
        } catch (\Exception $e) {
            logger()->error('Finnhub API Error (Holidays): ' . $e->getMessage());
            return collect();
        }
    }
}
