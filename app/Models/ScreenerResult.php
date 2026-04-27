<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScreenerResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'screener_id',
        'stock_id',
        'match_details',
    ];

    protected $casts = [
        'match_details' => 'array',
    ];

    /**
     * Get the screener that owns this result.
     */
    public function screener(): BelongsTo
    {
        return $this->belongsTo(Screener::class);
    }

    /**
     * Get the stock in this result.
     */
    public function stock(): BelongsTo
    {
        return $this->belongsTo(Stock::class);
    }
}
