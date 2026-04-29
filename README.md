# Stock Screener - Laravel 11 + Filament v3

Technical stock screening application for US markets with Alpaca & Finnhub API integration.

![Laravel](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=flat&logo=laravel)
![Filament](https://img.shields.io/badge/Filament-3.x-3C3C3C?style=flat)
![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=flat&logo=php)

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

- PHP 8.2+
- Composer 2.6+
- Node.js 18+
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
cp .env.example .env
php artisan key:generate
```

### Step 4: Configure Database & API Keys

Edit `.env` file:

```env
DB_CONNECTION=mysql
DB_DATABASE=stock_screener
DB_USERNAME=root
DB_PASSWORD=your_password

ALPACA_API_KEY=your_alpaca_key
ALPACA_API_SECRET=your_alpaca_secret
FINNHUB_API_KEY=your_finnhub_key
```

### Step 5: Create Database

```bash
mysql -u root -p -e "CREATE DATABASE stock_screener;"
```

### Step 6: Run Migrations

```bash
php artisan migrate
```

### Step 7: Create Admin User

```bash
php artisan make:filament-user
```

### Step 8: Run Server

```bash
php artisan serve
```

**Access Admin Panel:** `http://localhost:8000/admin`

---

## 📦 Import Stocks

```bash
# Import specific stocks (fast, no historical data)
php artisan stocks:import AAPL TSLA MSFT --skip-historical

# Import from watchlist.txt
php artisan stocks:import --all --skip-historical
```

---

## 🗂️ Database Tables

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
- **Free tier:** IEX feed only (not SIP)

### Finnhub API (Company Data)
- **Get key:** https://finnhub.io/dashboard
- **Base URL:** `https://finnhub.io/api/v1`
- **Free tier:** 60 calls/minute

---

## 📄 License

MIT License

---

**Built with ❤️ using Laravel 11 + Filament v3**
