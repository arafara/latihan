<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AlertLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'alert_id',
        'message',
        'channel',
        'sent',
        'error_message',
    ];

    protected $casts = [
        'sent' => 'boolean',
    ];

    /**
     * Get the alert that owns this log.
     */
    public function alert(): BelongsTo
    {
        return $this->belongsTo(Alert::class);
    }
}
