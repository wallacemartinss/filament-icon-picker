# Roadmap v2 - Filament Icon Picker

This document describes the planned features for version 2.x of Filament Icon Picker.

## Overview

Version 2 brings Filament v5 support and will implement significant improvements to the enum generation system, making it more flexible and portable.

---

## Planned Features

### 1. Enums in App Space (Outside Vendor)

**Current Problem:**
- Enums are generated in `vendor/wallacemartinss/filament-icon-picker/src/Enums/`
- They are lost on every `composer install` or `composer update`
- Fixed namespace doesn't allow autoloading when generated in another directory

**Proposed Solution:**
- Default path: `app/Enums/Icons/`
- Dynamic namespace based on chosen path
- Configuration via `config/filament-icon-picker.php`

**Configuration:**
```php
// config/filament-icon-picker.php
return [
    'enums' => [
        'path' => app_path('Enums/Icons'),
        'namespace' => 'App\\Enums\\Icons',
    ],
];
```

**Usage:**
```php
use App\Enums\Icons\Heroicons;

protected static string|BackedEnum|null $navigationIcon = Heroicons::OutlinedStar;
```

**Complexity:** Medium
**Breaking Changes:** No (configurable, keeps old default as fallback)

---

### 2. Custom Icon Collections

**Current Problem:**
- Can only generate enums for complete sets (thousands of icons)
- No way to create a collection with only desired icons
- Icon picker can get "flooded" with unnecessary icons

**Proposed Solution:**
- New interactive command to select specific icons
- Custom collections configuration
- Generate enums from collections

**Configuration:**
```php
// config/filament-icon-picker.php
return [
    'collections' => [
        'favorites' => [
            'heroicon-o-star',
            'heroicon-o-heart',
            'heroicon-o-user',
            'phosphor-whatsapp-logo',
            'fas-check',
        ],
        'social-media' => [
            'fab-facebook',
            'fab-twitter',
            'fab-instagram',
            'fab-linkedin',
            'fab-youtube',
            'phosphor-whatsapp-logo',
        ],
        'navigation' => [
            'heroicon-o-home',
            'heroicon-o-cog',
            'heroicon-o-user',
            'heroicon-o-bell',
            'heroicon-o-inbox',
        ],
    ],
];
```

**Commands:**
```bash
# Create collection interactively
php artisan filament-icon-picker:create-collection favorites

# Generate enum from collection
php artisan filament-icon-picker:generate-enums --collection=favorites

# List existing collections
php artisan filament-icon-picker:collections --list
```

**Usage in IconPickerField:**
```php
IconPickerField::make('icon')
    ->collection('favorites')  // Shows only icons from the collection
```

**Usage as Enum:**
```php
use App\Enums\Icons\Favorites;

Action::make('star')->icon(Favorites::Star);
```

**Complexity:** High
**Breaking Changes:** No (additional feature)

---

### 3. Enum Autodiscovery

**Current Problem:**
- Custom enums are not automatically recognized
- `Icon` helper doesn't know about user-created enums
- No integration between generated enums and the picker

**Proposed Solution:**
- Autodiscovery of enums in configured directory
- Automatic registration in IconSetManager
- Integration with `Icon` helper

**Configuration:**
```php
// config/filament-icon-picker.php
return [
    'enums' => [
        'autodiscover' => true,
        'path' => app_path('Enums/Icons'),
        'namespace' => 'App\\Enums\\Icons',
    ],
];
```

**How It Works:**
1. Plugin scans `app/Enums/Icons/` automatically
2. Registers all enums that implement `ScalableIcon`
3. Makes them available in `Icon` helper and picker

**Usage:**
```php
use Wallacemartinss\FilamentIconPicker\Enums\Icon;

// Access custom enum automatically
Icon::from('favorites', 'star');

// Or directly
use App\Enums\Icons\Favorites;
Favorites::Star->value;
```

**Complexity:** Medium
**Breaking Changes:** No (additional feature)

---

### 4. Simple Icons Support

**Current Problem:**
- No native support for Simple Icons package
- Users need to configure manually

**Proposed Solution:**
- Add `ublabs/blade-simple-icons` to supported packages
- Include prefix mapping for enum generation

**Implementation:**

```php
// InstallIconsCommand.php
'simple-icons' => [
    'package' => 'ublabs/blade-simple-icons',
    'name' => 'Simple Icons - Brand icons (~2,500 icons)',
    'sets' => ['simple-icons'],
],

// GenerateIconEnumsCommand.php
$prefixes = [
    // ... existing
    'simple-icons' => 'si-',
];
```

**Installation:**
```bash
php artisan filament-icon-picker:install-icons
# Select "Simple Icons" from the interactive menu
```

**Usage:**
```php
use App\Enums\Icons\SimpleIcons;

// Brand icons
SimpleIcons::Github;
SimpleIcons::Laravel;
SimpleIcons::Php;
SimpleIcons::Docker;
```

**Complexity:** Low
**Breaking Changes:** No (additional feature)

---

## Implementation Summary

| Feature | Complexity | Breaking? | Priority | Estimated Effort |
|---------|------------|-----------|----------|------------------|
| Enums in app space | Medium | No* | High | 2-3 hours |
| Custom collections | High | No | Medium | 4-6 hours |
| Autodiscovery | Medium | No | Medium | 2-3 hours |
| Simple Icons | Low | No | High | 30 minutes |

*Configurable - maintains backward compatibility

---

## Compatibility Notes

### What DOESN'T change:
- `IconPickerField` - continues working the same
- `IconPickerColumn` - continues working the same
- `IconPickerEntry` - continues working the same
- Helper `Icon::heroicon()`, `Icon::material()`, etc. - continue working
- API endpoints - remain the same

### What changes (optional):
- Default location of generated enums (configurable)
- Enum namespace (configurable)
- New features available (not mandatory)

---

## Migration from v1 to v2

### Step 1: Update package
```bash
composer require wallacemartinss/filament-icon-picker:^2.0
```

### Step 2: Publish new config (optional)
```bash
php artisan vendor:publish --tag="filament-icon-picker-config" --force
```

### Step 3: Regenerate enums in new location (optional)
```bash
php artisan filament-icon-picker:generate-enums --all
```

### Step 4: Update imports (if namespace changed)
```php
// Before (v1)
use Wallacemartinss\FilamentIconPicker\Enums\Heroicons;

// After (v2 - if configured app space)
use App\Enums\Icons\Heroicons;
```

---

## References

- [Original Issue](https://github.com/wallacemartinss/filament-icon-picker/issues) - Community suggestions
- [Blade Icons](https://blade-ui-kit.com/blade-icons) - Supported icon packages
- [Simple Icons](https://simpleicons.org/) - Brand icons
- [Filament v5](https://filamentphp.com/docs) - Filament documentation
