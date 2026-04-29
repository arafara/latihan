# Installation Checklist - Stock Screener v5.5.1

Follow this checklist step-by-step for a smooth installation.

---

## ✅ Pre-Installation Checklist

- [ ] PHP 8.3+ installed (`php -v`)
- [ ] Composer 2.8+ installed (`composer -V`)
- [ ] Node.js 20+ installed (`node -v`)
- [ ] MySQL 8.0+ or PostgreSQL 15+ running
- [ ] Git installed (`git --version`)

---

## 📥 Installation Steps

### Step 1: Clone Repository ✅

```bash
git clone https://github.com/arafara/latihan.git
cd latihan/stock-screener
```

**Verify:**
```bash
ls -la
# Should see: app/, config/, database/, .env.example, composer.json, README.md
```

---

### Step 2: Install Composer Dependencies ✅

```bash
composer install
```

**Expected Output:**
```
Package operations: 150+ installs
- Installing laravel/framework (v13.x.x)
- Installing filament/filament (v5.5.x)
...
```

**Verify:**
```bash
composer show | grep -E "laravel/framework|filament/filament"
# Should show:
# laravel/framework   v13.x.x
# filament/filament   v5.5.x
```

---

### Step 3: Install NPM Dependencies ✅

```bash
npm install
```

**Verify:**
```bash
ls node_modules | head
# Should show packages
```

---

### Step 4: Environment Setup ✅

```bash
cp .env.example .env
php artisan key:generate
```

**Verify:**
```bash
grep "APP_KEY=" .env
# Should show: APP_KEY=base64:xxxxxxxxxxxxx
```

---

### Step 5: Configure Database ✅

Edit `.env`:

```env
DB_CONNECTION=mysql
DB_DATABASE=stock_screener
DB_USERNAME=root
DB_PASSWORD=your_password
```

**Create Database:**
```bash
# MySQL
mysql -u root -p -e "CREATE DATABASE stock_screener CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

**Verify:**
```bash
php artisan env | grep DB_DATABASE
# Should show: stock_screener
```

---

### Step 6: Configure API Keys ✅

Edit `.env`:

```env
# Alpaca API
ALPACA_API_KEY=PKxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
ALPACA_API_SECRET=xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx

# Finnhub API
FINNHUB_API_KEY=xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
```

**Get API Keys:**
- Alpaca: https://app.alpaca.markets/paper/dashboard/overview
- Finnhub: https://finnhub.io/dashboard

---

### Step 7: Run Migrations ✅

```bash
php artisan migrate
```

**Expected Tables:**
```
✓ users
✓ stocks
✓ stock_prices
✓ technical_indicators
✓ watchlists
✓ stock_watchlist
✓ screeners
✓ screener_results
✓ alerts
✓ alert_logs
✓ cache
✓ sessions
✓ jobs
```

**Verify:**
```bash
php artisan migrate:status
# All migrations should show [ran]
```

---

### Step 8: Create Admin User ✅

```bash
php artisan make:filament-user
```

**Enter:**
```
Name: Admin
Email: admin@example.com
Password: password
```

**Verify:**
```bash
php artisan tinker
>>> App\Models\User::count()
# Should return: 1
```

---

### Step 9: Test Server ✅

```bash
php artisan serve
```

**Access:**
- Application: http://localhost:8000
- Admin Panel: http://localhost:8000/admin

**Login with:**
- Email: admin@example.com
- Password: password

---

### Step 10: Test Import Stocks ✅

```bash
# Import 5 test stocks (fast, no historical data)
php artisan stocks:import AAPL TSLA MSFT GOOGL AMZN --skip-historical
```

**Expected Output:**
```
Importing 5 stocks...
✓ Imported AAPL
✓ Imported TSLA
✓ Imported MSFT
✓ Imported GOOGL
✓ Imported AMZN
✅ Import completed!

+---------+-------+-------+
| Status  | Count | Total |
+---------+-------+-------+
| Success | 5     | 5     |
+---------+-------+-------+
```

**Verify in Admin Panel:**
```
http://localhost:8000/admin/resources/stocks
# Should show 5 stocks
```

---

## 🎯 Post-Installation

### Optional: Install Frontend Assets

```bash
npm run dev
# or for production
npm run build
```

### Optional: Setup Queue Worker

```bash
# Run in background
php artisan queue:work --daemon
```

### Optional: Setup Scheduler

```bash
# Add to crontab
* * * * * cd /path/to/stock-screener && php artisan schedule:run >> /dev/null 2>&1
```

---

## 🐛 Troubleshooting

### Issue: "No default Filament panel is set"

**Solution:**
```bash
php artisan optimize:clear
php artisan config:clear
php artisan cache:clear
```

### Issue: Composer conflicts

**Solution:**
```bash
# Delete vendor and lock file
rm -rf vendor composer.lock

# Clear composer cache
composer clear-cache

# Reinstall
composer install
```

### Issue: Migration errors

**Solution:**
```bash
# Fresh migrate (WARNING: deletes all data!)
php artisan migrate:fresh --seed
```

### Issue: Filament resources not showing

**Solution:**
```bash
# Check if files exist
ls app/Filament/Resources/

# Clear cache
php artisan optimize:clear
```

### Issue: API 403 errors

**Solution:**
- Alpaca free tier only allows IEX feed (not SIP)
- Use `--skip-historical` flag for import
- API keys might be invalid - check dashboard

---

## ✅ Final Verification

- [ ] Application loads at http://localhost:8000
- [ ] Admin panel accessible at http://localhost:8000/admin
- [ ] Can login with admin credentials
- [ ] Stocks menu shows in sidebar
- [ ] Alerts menu shows in sidebar
- [ ] Watchlists menu shows in sidebar
- [ ] Screeners menu shows in sidebar
- [ ] Can import stocks successfully
- [ ] Database has all tables

---

## 📚 Next Steps

After successful installation:

1. **Import your watchlist** - See README.md for import commands
2. **Configure alerts** - Set up price/indicator alerts
3. **Create screeners** - Build custom stock screeners
4. **Setup Telegram** - Configure bot for notifications

---

**Installation Complete! 🎉**

For support: https://github.com/arafara/latihan/issues
