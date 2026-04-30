# Filament v5.x Syntax - CORRECT!

## ✅ CORRECT Syntax for Filament v5.x (from official docs)

```php
<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AlertResource\Pages;
use App\Models\Alert;
use Filament\Resources\Resource;

class AlertResource extends Resource
{
    protected static string $model = Alert::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-bell';

    protected static string | \UnitEnum | null $navigationGroup = 'Alerts';

    protected static ?int $navigationSort = 1;

    // ... rest of the class
}
```

## ❌ WRONG Syntax (will cause errors)

```php
// Using ?string - ERROR in Filament v5.x!
protected static ?string $model = Alert::class;
protected static ?string $navigationIcon = 'heroicon-o-bell';
protected static ?string $navigationGroup = 'Alerts';
```

## Key Points

- **`$model`**: Must be `string` (no null, no Union)
- **`$navigationIcon`**: Must be `string | \BackedEnum | null`
- **`$navigationGroup`**: Must be `string | \UnitEnum | null`
- **`$navigationSort`**: Can be `?int` (nullable int)

## Source

From official Filament 5.x documentation:
https://filamentphp.com/docs/5.x/resources/overview
