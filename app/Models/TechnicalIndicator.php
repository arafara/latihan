<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TechnicalIndicator extends Model
{
    use HasFactory;

    protected $fillable = [
        'stock_id',
        'date',
        'sma_20',
        'sma_50',
        'sma_200',
        'ema_12',
        'ema_26',
        'rsi_14',
        'macd',
        'macd_signal',
        'macd_histogram',
        'stochastic_k',
        'stochastic_d',
        'bollinger_upper',
        'bollinger_middle',
        'bollinger_lower',
        'atr_14',
        'volume_sma_20',
        'obv',
        'change_percent',
        'week_52_high',
        'week_52_low',
    ];

    protected $casts = [
        'date' => 'date',
        'sma_20' => 'decimal:4',
        'sma_50' => 'decimal:4',
        'sma_200' => 'decimal:4',
        'ema_12' => 'decimal:4',
        'ema_26' => 'decimal:4',
        'rsi_14' => 'decimal:4',
        'macd' => 'decimal:6',
        'macd_signal' => 'decimal:6',
        'macd_histogram' => 'decimal:6',
        'stochastic_k' => 'decimal:4',
        'stochastic_d' => 'decimal:4',
        'bollinger_upper' => 'decimal:4',
        'bollinger_middle' => 'decimal:4',
        'bollinger_lower' => 'decimal:4',
        'atr_14' => 'decimal:4',
        'volume_sma_20' => 'integer',
        'obv' => 'integer',
        'change_percent' => 'decimal:4',
        'week_52_high' => 'decimal:4',
        'week_52_low' => 'decimal:4',
    ];

    /**
     * Get the stock that owns these indicators.
     */
    public function stock(): BelongsTo
    {
        return $this->belongsTo(Stock::class);
    }

    /**
     * Check if RSI is oversold.
     */
    public function isRsiOversold(float $threshold = 30): bool
    {
        return $this->rsi_14 !== null && $this->rsi_14 < $threshold;
    }

    /**
     * Check if RSI is overbought.
     */
    public function isRsiOverbought(float $threshold = 70): bool
    {
        return $this->rsi_14 !== null && $this->rsi_14 > $threshold;
    }

    /**
     * Check if MACD has bullish crossover.
     */
    public function hasBullishMacdCrossover(): bool
    {
        if ($this->macd === null || $this->macd_signal === null) {
            return false;
        }

        return $this->macd > $this->macd_signal;
    }

    /**
     * Check if MACD has bearish crossover.
     */
    public function hasBearishMacdCrossover(): bool
    {
        if ($this->macd === null || $this->macd_signal === null) {
            return false;
        }

        return $this->macd < $this->macd_signal;
    }

    /**
     * Check if price is above SMA 200.
     */
    public function isPriceAboveSma200(float $currentPrice): bool
    {
        return $this->sma_200 !== null && $currentPrice > $this->sma_200;
    }

    /**
     * Check if Golden Cross occurred (SMA 50 crosses above SMA 200).
     */
    public function hasGoldenCross(): bool
    {
        if ($this->sma_50 === null || $this->sma_200 === null) {
            return false;
        }

        return $this->sma_50 > $this->sma_200;
    }

    /**
     * Check if Death Cross occurred (SMA 50 crosses below SMA 200).
     */
    public function hasDeathCross(): bool
    {
        if ($this->sma_50 === null || $this->sma_200 === null) {
            return false;
        }

        return $this->sma_50 < $this->sma_200;
    }

    /**
     * Check if price is near 52-week high.
     */
    public function isNear52WeekHigh(float $currentPrice, float $threshold = 0.95): bool
    {
        if ($this->week_52_high === null || $this->week_52_high == 0) {
            return false;
        }

        return ($currentPrice / $this->week_52_high) >= $threshold;
    }

    /**
     * Check if price is near 52-week low.
     */
    public function isNear52WeekLow(float $currentPrice, float $threshold = 1.05): bool
    {
        if ($this->week_52_low === null || $this->week_52_low == 0) {
            return false;
        }

        return ($currentPrice / $this->week_52_low) <= $threshold;
    }

    /**
     * Check if volume is spiking (above average).
     */
    public function hasVolumeSpike(int $currentVolume, float $multiplier = 1.5): bool
    {
        if ($this->volume_sma_20 === null || $this->volume_sma_20 == 0) {
            return false;
        }

        return $currentVolume >= ($this->volume_sma_20 * $multiplier);
    }

    /**
     * Check if price is above Bollinger Band upper.
     */
    public function isAboveBollingerUpper(float $currentPrice): bool
    {
        return $this->bollinger_upper !== null && $currentPrice > $this->bollinger_upper;
    }

    /**
     * Check if price is below Bollinger Band lower.
     */
    public function isBelowBollingerLower(float $currentPrice): bool
    {
        return $this->bollinger_lower !== null && $currentPrice < $this->bollinger_lower;
    }
}
