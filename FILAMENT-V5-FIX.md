# Filament v5.5.1 Syntax Fix

## Problem

Filament v5.5.1 requires **type declarations** on all static properties.

## ✅ CORRECT Syntax for Filament v5.5.1

```php
<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AlertResource\Pages;
use App\Models\Alert;
use Filament\Resources\Resource;

class AlertResource extends Resource
{
    protected static ?string $model = Alert::class;

    protected static ?string $navigationIcon = 'heroicon-o-bell';

    protected static ?string $navigationGroup = 'Alerts';

    protected static ?int $navigationSort = 1;

    // ... rest of the class
}
```

## ❌ WRONG Syntax (will cause errors)

```php
// Missing type declarations - ERROR in Filament v5.5.1
protected static $model = Alert::class;
protected static $navigationIcon = 'heroicon-o-bell';
protected static $navigationGroup = 'Alerts';
```

## All Resources Updated

The following files have been fixed with correct syntax:

1. ✅ `app/Filament/Resources/StockResource.php`
2. ✅ `app/Filament/Resources/AlertResource.php`
3. ✅ `app/Filament/Resources/WatchlistResource.php`
4. ✅ `app/Filament/Resources/ScreenerResource.php`

All properties now have `?string` or `?int` type declarations.

## Installation

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

**Tested with Filament v5.5.1 ✅**
