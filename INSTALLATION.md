# Installation Guide - Laravel 13 + Filament 5

**PHP Required:** 8.3+

---

## Quick Install

```bash
git clone https://github.com/arafara/latihan.git
cd latihan/stock-screener
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan make:filament-user
php artisan serve
```

**Access:** http://localhost:8000/admin

---

## Step-by-Step

### 1. Check Requirements

```bash
php -v          # Must be 8.3+
composer -V     # 2.6+
```

### 2. Clone

```bash
git clone https://github.com/arafara/latihan.git
cd latihan/stock-screener
```

### 3. Install Composer

```bash
composer install
```

### 4. Environment

```bash
cp .env.example .env
php artisan key:generate
```

### 5. Database

Edit `.env`:
```env
DB_CONNECTION=mysql
DB_DATABASE=stock_screener
DB_USERNAME=root
DB_PASSWORD=
```

Create database:
```bash
mysql -u root -p -e "CREATE DATABASE stock_screener;"
```

### 6. API Keys

Edit `.env`:
```env
ALPACA_API_KEY=your_key
ALPACA_API_SECRET=your_secret
FINNHUB_API_KEY=your_key
```

### 7. Migrate

```bash
php artisan migrate
```

### 8. Admin User

```bash
php artisan make:filament-user
```

### 9. Run

```bash
php artisan serve
```

---

## 🐛 Troubleshooting

### Composer conflicts

```bash
rm -rf vendor composer.lock
composer clear-cache
composer install
```

### Cache

```bash
php artisan optimize:clear
```

---

## ✅ Test

```bash
php artisan stocks:import AAPL --skip-historical
```

---

**Done!** 🎉
