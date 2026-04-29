# Installation Guide - Laravel 13

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

## Detailed Steps

### 1. Requirements Check

```bash
php -v          # 8.2 or higher
composer -V     # 2.6 or higher
```

### 2. Clone Repository

```bash
git clone https://github.com/arafara/latihan.git
cd latihan/stock-screener
```

### 3. Install Dependencies

```bash
composer install
```

### 4. Environment Setup

```bash
cp .env.example .env
php artisan key:generate
```

### 5. Database Configuration

Edit `.env`:

```env
DB_CONNECTION=mysql
DB_DATABASE=stock_screener
DB_USERNAME=root
DB_PASSWORD=your_password
```

Create database:

```bash
mysql -u root -p -e "CREATE DATABASE stock_screener CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

### 6. API Keys

Edit `.env`:

```env
ALPACA_API_KEY=your_key_here
ALPACA_API_SECRET=your_secret_here
FINNHUB_API_KEY=your_key_here
```

Get keys:
- Alpaca: https://app.alpaca.markets/paper/dashboard/overview
- Finnhub: https://finnhub.io/dashboard

### 7. Run Migrations

```bash
php artisan migrate
```

### 8. Create Admin User

```bash
php artisan make:filament-user
```

Enter:
- Name: Admin
- Email: admin@example.com
- Password: password

### 9. Start Server

```bash
php artisan serve
```

---

## 🐛 Troubleshooting

### Composer Issues

```bash
rm -rf vendor composer.lock
composer clear-cache
composer install
```

### Cache Issues

```bash
php artisan optimize:clear
php artisan config:clear
php artisan cache:clear
```

---

## ✅ Test

```bash
php artisan stocks:import AAPL TSLA --skip-historical
```

Should import 2 stocks successfully.

---

**Done!** 🎉
