<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Alpaca Markets API Configuration
    |--------------------------------------------------------------------------
    */
    'alpaca' => [
        'key' => env('ALPACA_API_KEY'),
        'secret' => env('ALPACA_API_SECRET'),
        'base_url' => env('ALPACA_BASE_URL', 'https://data.alpaca.markets'),
        'paper_trading' => env('ALPACA_PAPER_TRADING', true),
        'timeout' => 30,
    ],

    /*
    |--------------------------------------------------------------------------
    | Finnhub API Configuration
    |--------------------------------------------------------------------------
    */
    'finnhub' => [
        'key' => env('FINNHUB_API_KEY'),
        'base_url' => env('FINNHUB_BASE_URL', 'https://finnhub.io/api/v1'),
        'timeout' => 30,
    ],

    /*
    |--------------------------------------------------------------------------
    | Telegram Bot Configuration (for alerts)
    |--------------------------------------------------------------------------
    */
    'telegram' => [
        'bot_token' => env('TELEGRAM_BOT_TOKEN'),
        'chat_id' => env('TELEGRAM_CHAT_ID'),
        'enabled' => env('TELEGRAM_BOT_TOKEN') && env('TELEGRAM_CHAT_ID'),
    ],
];
