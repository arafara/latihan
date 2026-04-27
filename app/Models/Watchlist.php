<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Watchlist extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'is_public',
    ];

    protected $casts = [
        'is_public' => 'boolean',
    ];

    /**
     * Get the user who owns this watchlist.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get stocks in this watchlist.
     */
    public function stocks(): BelongsToMany
    {
        return $this->belongsToMany(Stock::class, 'watchlist_items')
            ->withPivot('notes', 'position')
            ->orderByPivot('position')
            ->withTimestamps();
    }

    /**
     * Add a stock to the watchlist.
     */
    public function addStock(Stock $stock, ?string $notes = null): void
    {
        if (!$this->stocks()->where('stock_id', $stock->id)->exists()) {
            $maxPosition = $this->stocks()->max('watchlist_items.position') ?? 0;
            $this->stocks()->attach($stock->id, [
                'notes' => $notes,
                'position' => $maxPosition + 1,
            ]);
        }
    }

    /**
     * Remove a stock from the watchlist.
     */
    public function removeStock(Stock $stock): void
    {
        $this->stocks()->detach($stock->id);
    }

    /**
     * Get stock count.
     */
    public function getStockCountAttribute(): int
    {
        return $this->stocks()->count();
    }

    /**
     * Scope for user's watchlists.
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope for public watchlists.
     */
    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }
}
