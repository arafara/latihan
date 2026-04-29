# Stock Screener - Laravel 13 + Filament 5

Technical stock screening application for US markets with Alpaca & Finnhub API integration.

![Laravel](https://img.shields.io/badge/Laravel-13.x-FF2D20?style=flat&logo=laravel)
![Filament](https://img.shields.io/badge/Filament-5.x-3C3C3C?style=flat)
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

## 🚀 Quick Install

```bash
# 1. Clone
git clone https://github.com/arafara/latihan.git
cd latihan/stock-screener

# 2. Install
composer install
npm install

# 3. Setup
cp .env.example .env
php artisan key:generate

# 4. Edit .env (DB + API keys)

# 5. Migrate & Create User
php artisan migrate
php artisan make:filament-user

# 6. Run
php artisan serve
```

**Access:** http://localhost:8000/admin

---

## 📦 Import Stocks

```bash
# Import specific stocks (fast)
php artisan stocks:import AAPL TSLA MSFT --skip-historical

# Import from watchlist.txt
php artisan stocks:import --all --skip-historical
```

---

## 🗂️ Database Structure

- `stocks` - Stock symbols, names, sectors
- `stock_prices` - Daily OHLCV data
- `technical_indicators` - RSI, MACD, Moving Averages
- `watchlists` - User watchlists
- `screeners` - Screening configurations
- `screener_results` - Screening results
- `alerts` - Price/indicator alerts
- `alert_logs` - Alert trigger history

---

## 🔧 API Integration

### Alpaca API (Market Data)
- **Get keys:** https://app.alpaca.markets/paper/dashboard/overview
- **Base URL:** `https://data.alpaca.markets`
- **Free tier:** IEX feed only

### Finnhub API (Company Data)
- **Get key:** https://finnhub.io/dashboard
- **Base URL:** `https://finnhub.io/api/v1`
- **Free tier:** 60 calls/minute

---

## 📄 License

MIT License

---

**Built with ❤️ using Laravel 13 + Filament 5**
