# Installation Checklist - Stock Screener

**Version:** Laravel 11 + Filament v3  
**PHP:** 8.2+

---

## ✅ Quick Install

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

# 5. Migrate
php artisan migrate

# 6. Create admin user
php artisan make:filament-user

# 7. Run
php artisan serve
```

**Access:** http://localhost:8000/admin

---

## ✅ Detailed Steps

### Step 1: Requirements

```bash
php -v          # Should be 8.2+
composer -V     # Should be 2.6+
node -v         # Should be 18+
```

### Step 2: Clone

```bash
git clone https://github.com/arafara/latihan.git
cd latihan/stock-screener
```

### Step 3: Install Composer

```bash
composer install
```

**Expected:** No conflicts, all packages install successfully.

### Step 4: Install NPM

```bash
npm install
```

### Step 5: Environment

```bash
cp .env.example .env
php artisan key:generate
```

### Step 6: Database

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

### Step 7: API Keys

Edit `.env`:
```env
ALPACA_API_KEY=your_key_here
ALPACA_API_SECRET=your_secret_here
FINNHUB_API_KEY=your_key_here
```

### Step 8: Migrate

```bash
php artisan migrate
```

**Expected tables:**
- stocks
- stock_prices
- technical_indicators
- watchlists
- stock_watchlist
- screeners
- screener_results
- alerts
- alert_logs
- users
- cache
- sessions
- jobs

### Step 9: Admin User

```bash
php artisan make:filament-user
```

Enter:
- Name: Admin
- Email: admin@example.com
- Password: password

### Step 10: Test

```bash
php artisan serve
```

Access: http://localhost:8000/admin

---

## 🐛 Troubleshooting

### Composer conflicts

```bash
rm -rf vendor composer.lock
composer clear-cache
composer install
```

### Migration errors

```bash
php artisan migrate:fresh
```

### Filament errors

```bash
php artisan optimize:clear
php artisan config:clear
php artisan cache:clear
```

---

## ✅ Test Import

```bash
php artisan stocks:import AAPL TSLA MSFT --skip-historical
```

Should show:
```
✓ Imported AAPL
✓ Imported TSLA
✓ Imported MSFT
✅ Import completed!
```

---

**Installation Complete!** 🎉
