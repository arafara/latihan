<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Alert extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'stock_id',
        'type',
        'name',
        'conditions',
        'is_active',
        'notify_telegram',
        'notify_email',
        'last_triggered_at',
        'trigger_count',
    ];

    protected $casts = [
        'conditions' => 'array',
        'is_active' => 'boolean',
        'notify_telegram' => 'boolean',
        'notify_email' => 'boolean',
        'last_triggered_at' => 'datetime',
        'trigger_count' => 'integer',
    ];

    /**
     * Alert types.
     */
    const TYPE_PRICE_ABOVE = 'price_above';
    const TYPE_PRICE_BELOW = 'price_below';
    const TYPE_RSI_OVERSOLD = 'rsi_oversold';
    const TYPE_RSI_OVERBOUGHT = 'rsi_overbought';
    const TYPE_VOLUME_SPIKE = 'volume_spike';
    const TYPE_GOLDEN_CROSS = 'golden_cross';
    const TYPE_DEATH_CROSS = 'death_cross';
    const TYPE_MACD_BULLISH = 'macd_bullish';
    const TYPE_MACD_BEARISH = 'macd_bearish';
    const TYPE_NEW_HIGH = 'new_high';
    const TYPE_NEW_LOW = 'new_low';

    /**
     * Get available alert types.
     */
    public static function getTypes(): array
    {
        return [
            self::TYPE_PRICE_ABOVE => 'Price Above',
            self::TYPE_PRICE_BELOW => 'Price Below',
            self::TYPE_RSI_OVERSOLD => 'RSI Oversold',
            self::TYPE_RSI_OVERBOUGHT => 'RSI Overbought',
            self::TYPE_VOLUME_SPIKE => 'Volume Spike',
            self::TYPE_GOLDEN_CROSS => 'Golden Cross',
            self::TYPE_DEATH_CROSS => 'Death Cross',
            self::TYPE_MACD_BULLISH => 'MACD Bullish Crossover',
            self::TYPE_MACD_BEARISH => 'MACD Bearish Crossover',
            self::TYPE_NEW_HIGH => '52-Week High',
            self::TYPE_NEW_LOW => '52-Week Low',
        ];
    }

    /**
     * Get the user who owns this alert.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the stock for this alert.
     */
    public function stock(): BelongsTo
    {
        return $this->belongsTo(Stock::class);
    }

    /**
     * Get alert logs.
     */
    public function logs(): HasMany
    {
        return $this->hasMany(AlertLog::class);
    }

    /**
     * Log an alert trigger.
     */
    public function logTrigger(string $message, string $channel = 'telegram', bool $sent = true, ?string $errorMessage = null): void
    {
        $this->logs()->create([
            'message' => $message,
            'channel' => $channel,
            'sent' => $sent,
            'error_message' => $errorMessage,
        ]);

        if ($sent) {
            $this->increment('trigger_count');
            $this->update(['last_triggered_at' => now()]);
        }
    }

    /**
     * Scope for active alerts.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for user's alerts.
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope for alerts with telegram notification.
     */
    public function scopeNotifyTelegram($query)
    {
        return $query->where('notify_telegram', true);
    }
}
