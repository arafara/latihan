# Stock Screener - Laravel 13 + Filament v3

Technical stock screening application for US markets with Alpaca & Finnhub API integration.

![Laravel](https://img.shields.io/badge/Laravel-13.x-FF2D20?style=flat&logo=laravel)
![Filament](https://img.shields.io/badge/Filament-3.x-3C3C3C?style=flat)
![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=flat&logo=php)

---

## 🚀 Installation

### Prerequisites

- PHP 8.2+
- Composer 2.6+
- Node.js 18+
- MySQL 8.0+

### Step 1: Clone

```bash
git clone https://github.com/arafara/latihan.git
cd latihan/stock-screener
```

### Step 2: Install

```bash
composer install
npm install
```

### Step 3: Setup

```bash
cp .env.example .env
php artisan key:generate
```

### Step 4: Configure .env

```env
DB_CONNECTION=mysql
DB_DATABASE=stock_screener
DB_USERNAME=root
DB_PASSWORD=

ALPACA_API_KEY=your_alpaca_key
ALPACA_API_SECRET=your_alpaca_secret
FINNHUB_API_KEY=your_finnhub_key
```

### Step 5: Create Database

```bash
mysql -u root -p -e "CREATE DATABASE stock_screener;"
```

### Step 6: Migrate & Admin User

```bash
php artisan migrate
php artisan make:filament-user
```

### Step 7: Run

```bash
php artisan serve
```

**Access:** http://localhost:8000/admin

---

## 📦 Import Stocks

```bash
# Fast import (no historical data)
php artisan stocks:import AAPL TSLA MSFT --skip-historical

# Import from watchlist.txt
php artisan stocks:import --all --skip-historical
```

---

## 🗂️ Features

- ✅ Stock Management (CRUD)
- ✅ Technical Alerts (RSI, MACD)
- ✅ Watchlists
- ✅ Stock Screeners
- ✅ Alpaca API Integration
- ✅ Finnhub API Integration
- ✅ Telegram Notifications

---

## 📄 License

MIT

---

**Built with Laravel 13 + Filament v3**
