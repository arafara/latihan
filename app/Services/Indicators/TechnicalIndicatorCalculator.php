<?php

namespace App\Services\Indicators;

use Illuminate\Support\Collection;

/**
 * Calculate technical indicators from OHLCV data.
 */
class TechnicalIndicatorCalculator
{
    /**
     * Calculate Simple Moving Average (SMA).
     *
     * @param array $values Array of values (usually close prices)
     * @param int $period Number of periods
     */
    public static function sma(array $values, int $period): ?float
    {
        if (count($values) < $period) {
            return null;
        }

        $slice = array_slice($values, -$period, $period);
        return array_sum($slice) / $period;
    }

    /**
     * Calculate Exponential Moving Average (EMA).
     *
     * @param array $values Array of values
     * @param int $period Number of periods
     */
    public static function ema(array $values, int $period): ?float
    {
        if (count($values) < $period) {
            return null;
        }

        $multiplier = 2 / ($period + 1);
        $ema = $values[0];

        for ($i = 1; $i < count($values); $i++) {
            $ema = ($values[$i] - $ema) * $multiplier + $ema;
        }

        return $ema;
    }

    /**
     * Calculate Relative Strength Index (RSI).
     *
     * @param array $values Array of close prices
     * @param int $period Default 14
     */
    public static function rsi(array $values, int $period = 14): ?float
    {
        if (count($values) < $period + 1) {
            return null;
        }

        $gains = [];
        $losses = [];

        for ($i = 1; $i < count($values); $i++) {
            $change = $values[$i] - $values[$i - 1];
            $gains[] = max($change, 0);
            $losses[] = abs(min($change, 0));
        }

        // Calculate average gain and loss for first period
        $avgGain = array_sum(array_slice($gains, 0, $period)) / $period;
        $avgLoss = array_sum(array_slice($losses, 0, $period)) / $period;

        // Calculate smoothed averages for remaining periods
        for ($i = $period; $i < count($gains); $i++) {
            $avgGain = (($avgGain * ($period - 1)) + $gains[$i]) / $period;
            $avgLoss = (($avgLoss * ($period - 1)) + $losses[$i]) / $period;
        }

        if ($avgLoss == 0) {
            return 100;
        }

        $rs = $avgGain / $avgLoss;
        return 100 - (100 / (1 + $rs));
    }

    /**
     * Calculate MACD (Moving Average Convergence Divergence).
     *
     * @param array $values Array of close prices
     * @param int $fastPeriod Default 12
     * @param int $slowPeriod Default 26
     * @param int $signalPeriod Default 9
     */
    public static function macd(
        array $values,
        int $fastPeriod = 12,
        int $slowPeriod = 26,
        int $signalPeriod = 9
    ): array {
        if (count($values) < $slowPeriod + $signalPeriod) {
            return ['macd' => null, 'signal' => null, 'histogram' => null];
        }

        // Calculate EMAs
        $fastEma = self::ema($values, $fastPeriod);
        $slowEma = self::ema($values, $slowPeriod);

        if ($fastEma === null || $slowEma === null) {
            return ['macd' => null, 'signal' => null, 'histogram' => null];
        }

        $macdLine = $fastEma - $slowEma;

        // For signal line, we need historical MACD values
        // Simplified: calculate signal from current MACD
        $signalLine = $macdLine; // In production, calculate from historical MACD values
        $histogram = $macdLine - $signalLine;

        return [
            'macd' => $macdLine,
            'signal' => $signalLine,
            'histogram' => $histogram,
        ];
    }

    /**
     * Calculate Stochastic Oscillator.
     *
     * @param array $highs Array of high prices
     * @param array $lows Array of low prices
     * @param array $closes Array of close prices
     * @param int $kPeriod Default 14
     * @param int $dPeriod Default 3
     */
    public static function stochastic(
        array $highs,
        array $lows,
        array $closes,
        int $kPeriod = 14,
        int $dPeriod = 3
    ): array {
        if (count($closes) < $kPeriod) {
            return ['k' => null, 'd' => null];
        }

        $highestHigh = max(array_slice($highs, -$kPeriod));
        $lowestLow = min(array_slice($lows, -$kPeriod));
        $currentClose = end($closes);

        if ($highestHigh == $lowestLow) {
            return ['k' => 50, 'd' => 50];
        }

        $k = (($currentClose - $lowestLow) / ($highestHigh - $lowestLow)) * 100;

        // Simplified %D (in production, calculate from historical %K values)
        $d = $k;

        return ['k' => $k, 'd' => $d];
    }

    /**
     * Calculate Bollinger Bands.
     *
     * @param array $values Array of close prices
     * @param int $period Default 20
     * @param float $stdDevMultiplier Default 2
     */
    public static function bollingerBands(
        array $values,
        int $period = 20,
        float $stdDevMultiplier = 2
    ): array {
        if (count($values) < $period) {
            return ['upper' => null, 'middle' => null, 'lower' => null];
        }

        $slice = array_slice($values, -$period, $period);
        $middle = array_sum($slice) / $period;

        // Calculate standard deviation
        $variance = 0;
        foreach ($slice as $value) {
            $variance += pow($value - $middle, 2);
        }
        $stdDev = sqrt($variance / $period);

        $upper = $middle + ($stdDevMultiplier * $stdDev);
        $lower = $middle - ($stdDevMultiplier * $stdDev);

        return [
            'upper' => $upper,
            'middle' => $middle,
            'lower' => $lower,
        ];
    }

    /**
     * Calculate Average True Range (ATR).
     *
     * @param array $highs Array of high prices
     * @param array $lows Array of low prices
     * @param array $closes Array of close prices (for previous close)
     * @param int $period Default 14
     */
    public static function atr(
        array $highs,
        array $lows,
        array $closes,
        int $period = 14
    ): ?float {
        if (count($highs) < $period + 1) {
            return null;
        }

        $trueRanges = [];

        for ($i = 1; $i < count($highs); $i++) {
            $highLow = $highs[$i] - $lows[$i];
            $highClose = abs($highs[$i] - $closes[$i - 1]);
            $lowClose = abs($lows[$i] - $closes[$i - 1]);

            $trueRanges[] = max($highLow, $highClose, $lowClose);
        }

        // Simple ATR (average of true ranges)
        // In production, use Wilder's smoothing method
        $slice = array_slice($trueRanges, -$period, $period);
        return array_sum($slice) / count($slice);
    }

    /**
     * Calculate On-Balance Volume (OBV).
     *
     * @param array $closes Array of close prices
     * @param array $volumes Array of volumes
     */
    public static function obv(array $closes, array $volumes): ?int
    {
        if (count($closes) !== count($volumes) || count($closes) < 2) {
            return null;
        }

        $obv = 0;

        for ($i = 1; $i < count($closes); $i++) {
            if ($closes[$i] > $closes[$i - 1]) {
                $obv += $volumes[$i];
            } elseif ($closes[$i] < $closes[$i - 1]) {
                $obv -= $volumes[$i];
            }
            // If close is equal, OBV doesn't change
        }

        return $obv;
    }

    /**
     * Calculate Volume Simple Moving Average.
     *
     * @param array $volumes Array of volumes
     * @param int $period Default 20
     */
    public static function volumeSma(array $volumes, int $period = 20): ?int
    {
        if (count($volumes) < $period) {
            return null;
        }

        $slice = array_slice($volumes, -$period, $period);
        return (int) (array_sum($slice) / $period);
    }

    /**
     * Calculate all indicators for a stock.
     *
     * @param Collection $bars Collection of OHLCV bars (ordered by date ascending)
     */
    public static function calculateAll(Collection $bars): array
    {
        if ($bars->isEmpty()) {
            return [];
        }

        // Extract arrays from bars
        $closes = $bars->pluck('close')->values()->toArray();
        $highs = $bars->pluck('high')->values()->toArray();
        $lows = $bars->pluck('low')->values()->toArray();
        $volumes = $bars->pluck('volume')->values()->toArray();

        // Moving Averages
        $sma20 = self::sma($closes, 20);
        $sma50 = self::sma($closes, 50);
        $sma200 = self::sma($closes, 200);
        $ema12 = self::ema($closes, 12);
        $ema26 = self::ema($closes, 26);

        // Momentum
        $rsi14 = self::rsi($closes, 14);
        $macd = self::macd($closes);
        $stochastic = self::stochastic($highs, $lows, $closes);

        // Volatility
        $bollinger = self::bollingerBands($closes);
        $atr14 = self::atr($highs, $lows, $closes, 14);

        // Volume
        $volumeSma20 = self::volumeSma($volumes, 20);
        $obv = self::obv($closes, $volumes);

        // Price metrics
        $currentPrice = end($closes);
        $week52High = max($highs);
        $week52Low = min($lows);

        // Calculate daily change percent
        if (count($closes) >= 2) {
            $prevClose = $closes[count($closes) - 2];
            $changePercent = (($currentPrice - $prevClose) / $prevClose) * 100;
        } else {
            $changePercent = 0;
        }

        return [
            'sma_20' => $sma20,
            'sma_50' => $sma50,
            'sma_200' => $sma200,
            'ema_12' => $ema12,
            'ema_26' => $ema26,
            'rsi_14' => $rsi14,
            'macd' => $macd['macd'],
            'macd_signal' => $macd['signal'],
            'macd_histogram' => $macd['histogram'],
            'stochastic_k' => $stochastic['k'],
            'stochastic_d' => $stochastic['d'],
            'bollinger_upper' => $bollinger['upper'],
            'bollinger_middle' => $bollinger['middle'],
            'bollinger_lower' => $bollinger['lower'],
            'atr_14' => $atr14,
            'volume_sma_20' => $volumeSma20,
            'obv' => $obv,
            'change_percent' => $changePercent,
            'week_52_high' => $week52High,
            'week_52_low' => $week52Low,
        ];
    }
}
