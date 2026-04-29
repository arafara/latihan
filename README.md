# Stock Screener - Laravel 13 + Filament v5.5.1

Technical stock screening application for US markets with Alpaca & Finnhub API integration.

![Laravel](https://img.shields.io/badge/Laravel-13.x-FF2D20?style=flat&logo=laravel)
![Filament](https://img.shields.io/badge/Filament-5.5.1-3C3C3C?style=flat)
![PHP](https://img.shields.io/badge/PHP-8.3+-777BB4?style=flat&logo=php)

---

## ✨ Features

- 📊 **Stock Management** - CRUD via Filament admin panel
- 🔔 **Price & Technical Alerts** - RSI, MACD, Moving Averages
- 📈 **Watchlists** - Organize stocks into custom lists
- 🎯 **Technical Screeners** - Filter stocks by indicators
- 📉 **Historical Data** - Import from Alpaca API
- 🔔 **Telegram Notifications** - Real-time alert notifications

---

## 🚀 Installation

### Prerequisites

- PHP 8.3+
- Composer 2.8+
- Node.js 20+
- MySQL 8.0+ or PostgreSQL 15+

### Step 1: Clone Repository

```bash
git clone https://github.com/arafara/latihan.git
cd latihan/stock-screener
```

### Step 2: Install Dependencies

```bash
composer install
npm install
```

### Step 3: Environment Setup

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### Step 4: Configure Database & API Keys

Edit `.env` file:

```env
# Database
DB_CONNECTION=mysql
DB_DATABASE=stock_screener
DB_USERNAME=root
DB_PASSWORD=your_password

# Alpaca API (Market Data)
ALPACA_API_KEY=your_alpaca_key
ALPACA_API_SECRET=your_alpaca_secret

# Finnhub API (Company Data)
FINNHUB_API_KEY=your_finnhub_key
```

### Step 5: Create Database

```bash
# MySQL
mysql -u root -p -e "CREATE DATABASE stock_screener;"
```

### Step 6: Run Migrations

```bash
php artisan migrate
```

### Step 7: Create Admin User

```bash
php artisan make:filament-user

# Enter:
# Name: Admin
# Email: admin@example.com
# Password: password
```

### Step 8: Run Server

```bash
# Terminal 1 - Backend
php artisan serve

# Terminal 2 - Frontend (optional, for Vite)
npm run dev
```

**Access Admin Panel:** `http://localhost:8000/admin`

---

## 📦 Import Stocks

### Import Specific Stocks

```bash
# Import without historical data (fast)
php artisan stocks:import AAPL TSLA MSFT GOOGL AMZN --skip-historical

# Import with historical data (slower, requires valid API)
php artisan stocks:import AAPL TSLA MSFT
```

### Import from Watchlist File

Create `storage/app/watchlist.txt`:

```
AAPL
TSLA
MSFT
GOOGL
AMZN
NVDA
META
NFLX
```

Run import:

```bash
php artisan stocks:import --all --skip-historical
```

### Command Options

| Option | Description |
|--------|-------------|
| `--skip-historical` | Skip fetching historical price data |
| `--skip-indicators` | Skip calculating technical indicators |
| `--all` | Import all stocks from watchlist.txt |

---

## 🗂️ Database Structure

### Tables

| Table | Description |
|-------|-------------|
| `stocks` | Stock symbols, names, sectors |
| `stock_prices` | Daily OHLCV data |
| `technical_indicators` | RSI, MACD, Moving Averages, etc. |
| `watchlists` | User watchlists |
| `stock_watchlist` | Pivot table for stocks ↔ watchlists |
| `screeners` | Screening configurations |
| `screener_results` | Screening results |
| `alerts` | Price/indicator alerts |
| `alert_logs` | Alert trigger history |
| `users` | Admin users |

---

## 🔧 API Integration

### Alpaca API (Market Data)

- **Base URL:** `https://data.alpaca.markets`
- **Endpoints Used:**
  - `/v2/stocks/{symbol}/quote` - Real-time quote
  - `/v2/stocks/{symbol}/snapshot` - Full snapshot
  - `/v2/stocks/{symbol}/bars` - Historical bars

**Free Tier Limitations:**
- IEX feed only (not SIP)
- 403 error on recent SIP data (handled gracefully)

### Finnhub API (Company Data)

- **Base URL:** `https://finnhub.io/api/v1`
- **Endpoints Used:**
  - `/stock/profile2` - Company profile
  - `/quote` - Real-time quote
  - `/stock/peers` - Competitor stocks

**Free Tier Limitations:**
- 60 API calls/minute
- Basic fundamentals only

---

## 🎯 Filament v5.5.1 Compatibility

All Filament resources use **no type declarations** on static properties:

```php
// ✅ CORRECT for Filament v5
class StockResource extends Resource
{
    protected static $model = Stock::class;
    protected static $navigationIcon = 'heroicon-o-chart-bar';
    protected static $navigationGroup = 'Stock Management';
}

// ❌ WRONG - Will cause errors
protected static ?string $model = Stock::class;
```

---

## 🛠️ Development

### Run Tests

```bash
php artisan test
```

### Code Style

```bash
composer lint
composer format
```

### Clear Cache

```bash
php artisan optimize:clear
```

---

## 📄 License

MIT License - see [LICENSE](LICENSE) file for details.

---

## 🤝 Support

For issues or questions:
- GitHub Issues: https://github.com/arafara/latihan/issues
- Email: admin@example.com

---

**Built with ❤️ using Laravel 13 + Filament v5.5.1**
