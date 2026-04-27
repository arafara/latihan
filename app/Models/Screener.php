<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Screener extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'filters',
        'is_public',
        'is_active',
        'last_run_at',
        'last_result_count',
    ];

    protected $casts = [
        'filters' => 'array',
        'is_public' => 'boolean',
        'is_active' => 'boolean',
        'last_run_at' => 'datetime',
        'last_result_count' => 'integer',
    ];

    /**
     * Get the user who owns this screener.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get screener results.
     */
    public function results(): HasMany
    {
        return $this->hasMany(ScreenerResult::class);
    }

    /**
     * Get current results with stock data.
     */
    public function getCurrentResultsAttribute()
    {
        return $this->results()->with('stock')->get();
    }

    /**
     * Run the screener and return matching stocks.
     */
    public function run(): \Illuminate\Support\Collection
    {
        // This will be implemented in the service
        // For now, return empty collection
        return collect();
    }

    /**
     * Get filter categories.
     */
    public function getFilterCategoriesAttribute(): array
    {
        return array_keys($this->filters ?? []);
    }

    /**
     * Scope for active screeners.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for user's screeners.
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}
