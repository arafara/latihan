<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockPrice extends Model
{
    use HasFactory;

    protected $fillable = [
        'stock_id',
        'date',
        'open',
        'high',
        'low',
        'close',
        'volume',
        'vwap',
        'trade_count',
    ];

    protected $casts = [
        'date' => 'date',
        'open' => 'decimal:4',
        'high' => 'decimal:4',
        'low' => 'decimal:4',
        'close' => 'decimal:4',
        'volume' => 'integer',
        'vwap' => 'decimal:4',
        'trade_count' => 'integer',
    ];

    /**
     * Get the stock that owns this price.
     */
    public function stock(): BelongsTo
    {
        return $this->belongsTo(Stock::class);
    }

    /**
     * Calculate daily price change.
     */
    public function getChangePercentAttribute(): ?float
    {
        $previous = static::where('stock_id', $this->stock_id)
            ->where('date', '<', $this->date)
            ->orderBy('date', 'desc')
            ->first();

        if (!$previous || $previous->close == 0) {
            return null;
        }

        return round((($this->close - $previous->close) / $previous->close) * 100, 4);
    }

    /**
     * Calculate daily price change (absolute).
     */
    public function getChangeAttribute(): ?float
    {
        $previous = static::where('stock_id', $this->stock_id)
            ->where('date', '<', $this->date)
            ->orderBy('date', 'desc')
            ->first();

        if (!$previous) {
            return null;
        }

        return round($this->close - $previous->close, 4);
    }

    /**
     * Check if this is a gap up day.
     */
    public function isGapUp(): bool
    {
        $previous = static::where('stock_id', $this->stock_id)
            ->where('date', '<', $this->date)
            ->orderBy('date', 'desc')
            ->first();

        if (!$previous) {
            return false;
        }

        return $this->open > $previous->close;
    }

    /**
     * Check if this is a gap down day.
     */
    public function isGapDown(): bool
    {
        $previous = static::where('stock_id', $this->stock_id)
            ->where('date', '<', $this->date)
            ->orderBy('date', 'desc')
            ->first();

        if (!$previous) {
            return false;
        }

        return $this->open < $previous->close;
    }
}
