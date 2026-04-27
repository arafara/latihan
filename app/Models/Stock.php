<?php

namespace App\Models;

use Database\Factories\StockFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Stock extends Model
{
    use HasFactory;

    protected $fillable = [
        'symbol',
        'name',
        'exchange',
        'sector',
        'industry',
        'market_cap',
        'is_active',
    ];

    protected $casts = [
        'market_cap' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Get price data for this stock.
     */
    public function prices(): HasMany
    {
        return $this->hasMany(StockPrice::class)->orderBy('date', 'desc');
    }

    /**
     * Get latest price record.
     */
    public function latestPrice()
    {
        return $this->hasOne(StockPrice::class)->latestOfMany();
    }

    /**
     * Get technical indicators.
     */
    public function indicators(): HasMany
    {
        return $this->hasMany(TechnicalIndicator::class)->orderBy('date', 'desc');
    }

    /**
     * Get latest indicators.
     */
    public function latestIndicators()
    {
        return $this->hasOne(TechnicalIndicator::class)->latestOfMany();
    }

    /**
     * Get watchlists containing this stock.
     */
    public function watchlists(): BelongsToMany
    {
        return $this->belongsToMany(Watchlist::class, 'watchlist_items')
            ->withPivot('notes', 'position')
            ->withTimestamps();
    }

    /**
     * Get alerts for this stock.
     */
    public function alerts(): HasMany
    {
        return $this->hasMany(Alert::class);
    }

    /**
     * Scope for active stocks only.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for specific exchange.
     */
    public function scopeExchange($query, $exchange)
    {
        return $query->where('exchange', $exchange);
    }

    /**
     * Scope for specific sector.
     */
    public function scopeSector($query, $sector)
    {
        return $query->where('sector', $sector);
    }

    /**
     * Check if stock is in any user watchlist.
     */
    public function isInWatchlist(): bool
    {
        return $this->watchlists()->exists();
    }
}
