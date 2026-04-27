<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Stock Screener</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }
        .container {
            background: white;
            padding: 3rem;
            border-radius: 1rem;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            text-align: center;
            max-width: 600px;
        }
        h1 {
            color: #333;
            margin-bottom: 1rem;
            font-size: 2.5rem;
        }
        p {
            color: #666;
            line-height: 1.6;
            margin-bottom: 2rem;
        }
        .btn {
            display: inline-block;
            background: #667eea;
            color: white;
            padding: 1rem 2rem;
            border-radius: 0.5rem;
            text-decoration: none;
            font-weight: 600;
            transition: transform 0.2s;
        }
        .btn:hover {
            transform: translateY(-2px);
            background: #5568d3;
        }
        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
            margin-top: 2rem;
            text-align: left;
        }
        .feature {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 0.5rem;
            font-size: 0.9rem;
        }
        .feature strong {
            color: #667eea;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📈 Stock Screener</h1>
        <p>
            Technical stock screening powered by <strong>Alpaca</strong> & <strong>Finnhub</strong> APIs.
            Screen 200+ US stocks with advanced technical indicators.
        </p>
        
        <a href="/admin" class="btn">Open Admin Panel →</a>
        
        <div class="features">
            <div class="feature"><strong>📊</strong> 15+ Indicators</div>
            <div class="feature"><strong>🔍</strong> Multi-Filter</div>
            <div class="feature"><strong>⚡</strong> Real-time Data</div>
            <div class="feature"><strong>🔔</strong> Price Alerts</div>
            <div class="feature"><strong>📱</strong> Watchlists</div>
            <div class="feature"><strong>📉</strong> Charts</div>
        </div>
    </div>
</body>
</html>
