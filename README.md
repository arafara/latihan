# 📈 Stock Screener

**Technical Stock Screening for US Markets**

A powerful stock screening application built with Laravel 13 + Filament 3, integrating Alpaca and Finnhub APIs for real-time market data and technical analysis.

> 🚧 **Status:** Phase 1 - Foundation (In Progress)

---

## 🎯 Features

### Technical Indicators (15+)
| Category | Indicators |
|----------|------------|
| **Trend** | SMA (20, 50, 200), EMA (12, 26), Price vs MA, MA Crossover |
| **Momentum** | RSI (14), MACD, Stochastic, CCI |
| **Volume** | Volume SMA, OBV, Volume Spike Detection |
| **Volatility** | Bollinger Bands, ATR (14) |
| **Price Action** | 52-Week High/Low, Gap Detection, Change % |

### Screening
- Multi-filter screening (combine multiple indicators)
- Save screener presets
- Export results to CSV/Excel
- Real-time scan on-demand
- Scheduled scans (daily/weekly)

### Watchlist Management
- Import 200+ stocks
- Multiple watchlists with categories
- Quick view key metrics
- Notes per stock

### Alerts & Notifications
- Price alerts (above/below threshold)
- Indicator alerts (RSI oversold/overbought, volume spike, etc.)
- Telegram bot integration
- Email notifications (optional)

### Data Sources
- **Alpaca API**: Real-time price data, historical OHLCV
- **Finnhub API**: Fundamentals, company profile, news, sentiment

---

## 🏗️ Architecture

```
stock-screener/
├── app/
│   ├── Models/
│   │   ├── Stock.php              # Stock symbol, name, sector
│   │   ├── StockPrice.php         # Daily OHLCV data
│   │   ├── TechnicalIndicator.php # Calculated indicators
│   │   ├── Watchlist.php          # User watchlists
│   │   ├── Screener.php           # Saved screeners
│   │   ├── Alert.php              # Price/indicator alerts
│   │   └── User.php               # Filament user
│   ├── Services/
│   │   ├── Alpaca/
│   │   │   └── AlpacaService.php  # Alpaca API integration
│   │   ├── Finnhub/
│   │   │   └── FinnhubService.php # Finnhub API integration
│   │   └── Indicators/
│   │       └── TechnicalIndicatorCalculator.php
│   ├── Filament/
│   │   ├── Resources/             # Admin CRUD resources
│   │   └── Widgets/               # Dashboard widgets
│   └── Livewire/                  # Livewire components
├── database/
│   ├── migrations/                # Database schema
│   ├── factories/                 # Test data factories
│   └── seeders/                   # Database seeders
└── config/
    └── services.php               # API configurations
```

---

## 🗄️ Database Schema

### Core Tables

**stocks**
- symbol, name, exchange, sector, industry, market_cap

**stock_prices**
- stock_id, date, open, high, low, close, volume, vwap

**technical_indicators**
- stock_id, date, SMA/EMA, RSI, MACD, Bollinger, ATR, OBV, etc.

**watchlists & watchlist_items**
- User watchlists with stocks

**screeners & screener_results**
- Saved screener configurations and results

**alerts & alert_logs**
- Price/indicator alerts with notification logs

---

## 🚀 Setup Instructions

### Prerequisites

- PHP 8.2+
- Composer 2.x
- Node.js 18+
- SQLite/MySQL

### Installation

1. **Clone and install dependencies**
```bash
cd stock-screener
composer install
npm install
```

2. **Environment setup**
```bash
cp .env.example .env
php artisan key:generate
```

3. **Configure API keys** (`.env`)
```env
# Alpaca API
ALPACA_API_KEY=your_alpaca_key
ALPACA_API_SECRET=your_alpaca_secret

# Finnhub API
FINNHUB_API_KEY=your_finnhub_key

# Telegram (optional)
TELEGRAM_BOT_TOKEN=your_bot_token
TELEGRAM_CHAT_ID=your_chat_id
```

4. **Database setup**
```bash
touch database/database.sqlite
php artisan migrate
```

5. **Build assets**
```bash
npm run dev  # Development
npm run build # Production
```

6. **Start server**
```bash
php artisan serve
```

Visit: http://localhost:8000/admin

---

## 📊 API Integration

### Alpaca API (Market Data)

| Method | Endpoint | Purpose |
|--------|----------|---------|
| `getQuote()` | `/v2/stocks/{symbol}/quotes/latest` | Latest quote |
| `getBars()` | `/v2/stocks/{symbol}/bars` | OHLCV historical data |
| `getMultipleBars()` | `/v2/stocks/multi/bars` | Batch bars for multiple symbols |
| `getSnapshot()` | `/v2/stocks/{symbol}/snapshot` | Complete snapshot |
| `getMultipleSnapshots()` | `/v2/stocks/multi/snapshot` | Batch snapshots |

**Rate Limit:** 200 calls/minute (free tier)

### Finnhub API (Fundamentals)

| Method | Endpoint | Purpose |
|--------|----------|---------|
| `getQuote()` | `/quote` | Real-time quote |
| `getCompanyProfile()` | `/stock/profile2` | Company info |
| `getCompanyMetrics()` | `/stock/metric` | P/E, P/B, ROE, etc. |
| `screener()` | `/stock/screener` | Stock screener |
| `getCandles()` | `/stock/candle` | OHLCV data |
| `getCompanyNews()` | `/company-news` | Company news |

**Rate Limit:** 60 calls/minute (free tier)

---

## 🔧 Development Phases

### ✅ Phase 1: Foundation (Current)
- [x] Project scaffold
- [x] Database schema
- [x] Alpaca API service
- [x] Finnhub API service
- [x] Technical indicator calculator
- [ ] Models complete
- [ ] Filament resources

### 🔄 Phase 2: Core Features
- [ ] Import watchlist (200 stocks)
- [ ] Fetch & cache price data
- [ ] Calculate indicators daily
- [ ] Stock screener UI
- [ ] Results table with export

### 📋 Phase 3: Advanced Features
- [ ] Price alerts
- [ ] Telegram notifications
- [ ] Chart visualization (TradingView)
- [ ] Save screener presets
- [ ] Dashboard widgets

### 📋 Phase 4: Polish
- [ ] Performance optimization
- [ ] Unit/Feature tests
- [ ] Documentation
- [ ] Deployment setup

---

## 📝 Usage Examples

### Fetch Stock Data
```php
use App\Services\Alpaca\AlpacaService;
use App\Services\Finnhub\FinnhubService;

$alpaca = new AlpacaService();
$finnhub = new FinnhubService();

// Get latest quote
$quote = $alpaca->getQuote('AAPL');

// Get historical bars (200 days)
$bars = $alpaca->getBars('AAPL', '1Day', now()->subDays(200), now(), 200);

// Calculate indicators
use App\Services\Indicators\TechnicalIndicatorCalculator;
$indicators = TechnicalIndicatorCalculator::calculateAll($bars);

// Get company fundamentals
$metrics = $finnhub->getCompanyMetrics('AAPL');
```

### Screen for Oversold Stocks
```php
// RSI < 30, Price above SMA 200, Volume spike
$screened = $stocks->filter(function($stock) {
    $indicators = $stock->latestIndicators;
    return $indicators->rsi_14 < 30
        && $indicators->sma_200
        && $indicators->hasVolumeSpike($currentVolume, 1.5);
});
```

---

## 🎯 Screeners (Preset Ideas)

### Momentum Play
- RSI < 30 (oversold)
- Price > SMA 200 (uptrend)
- Volume > 1.5x average

### Breakout Play
- Price near 52-week high (>95%)
- Volume spike > 2x average
- MACD bullish crossover

### Golden Cross
- SMA 50 > SMA 200
- Price > SMA 20 and SMA 50
- RSI between 40-70

### Value Play
- P/E < 20 (from Finnhub)
- P/B < 3
- ROE > 15%

---

## 📄 License

MIT License

---

**Built with ❤️ by Fara**

*Last Updated: April 2026*
