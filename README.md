# Filament Icon Picker

[![Latest Version on Packagist](https://img.shields.io/packagist/v/wallacemartinss/filament-icon-picker.svg?style=flat-square)](https://packagist.org/packages/wallacemartinss/filament-icon-picker)
[![Total Downloads](https://img.shields.io/packagist/dt/wallacemartinss/filament-icon-picker.svg?style=flat-square)](https://packagist.org/packages/wallacemartinss/filament-icon-picker)

A beautiful, modern icon picker component for Filament v4, powered by [blade-ui-kit/blade-icons](https://github.com/blade-ui-kit/blade-icons).

![Icon Picker Preview](art/preview.png)

## Features

- 🎨 **Beautiful Modal Interface** - Modern, responsive grid layout with smooth animations
- 🔍 **Smart Search** - Real-time filtering by icon name
- 📦 **Multiple Icon Sets** - Support for all blade-icons packages (Heroicons, FontAwesome, Phosphor, Material, etc.)
- 🎯 **Set Filtering** - Filter icons by their provider using dropdown
- ⚡ **Infinite Scroll** - Performance-optimized with lazy loading
- 🖼️ **Preview** - Shows selected icon in the field
- 📋 **Form Field** - Use in Filament forms
- 📊 **Table Column** - Display icons in tables
- 📝 **Infolist Entry** - Show icons in infolists (read-only)
- ⚙️ **Configurable** - Customize modal size, columns, and available icon sets

## Requirements

- PHP 8.2+
- Laravel 11+
- Filament 4.0+
- blade-ui-kit/blade-icons 1.0+

## Installation

### Step 1: Install the package via Composer

```bash
composer require wallacemartinss/filament-icon-picker
```

### Step 2: Install icon packages (Interactive)

You need at least one icon package to use the Icon Picker. Use the interactive installer:

```bash
php artisan filament-icon-picker:install-icons
```

This will show you an interactive menu to select which icon packages to install:

```
🎨 Filament Icon Picker - Install Icons

? Select icon packages to install:
  ● Heroicons - Heroicons by Tailwind CSS (~1,300 icons)
  ○ Fontawesome - Font Awesome (Solid, Regular, Brands) (~2,800 icons)
  ○ Phosphor - Phosphor Icons (~9,000 icons)
  ○ Material - Google Material Design (~10,000 icons)
  ○ Tabler - Tabler Icons (~4,400 icons)
  ○ Lucide - Lucide Icons (~1,400 icons)
```

**Other options:**

```bash
# List available packages and their status
php artisan filament-icon-picker:install-icons --list

# Install all icon packages at once
php artisan filament-icon-picker:install-icons --all
```

**Or install manually via Composer:**

```bash
# Heroicons (recommended)
composer require blade-ui-kit/blade-heroicons

# Font Awesome (2800+ icons)
composer require owenvoke/blade-fontawesome

# Phosphor Icons (9000+ icons)
composer require codeat3/blade-phosphor-icons

# Google Material Design Icons (10000+ icons)
composer require codeat3/blade-google-material-design-icons

# Tabler Icons (4400+ icons)
composer require blade-ui-kit/blade-tabler-icons

# Lucide Icons (1400+ icons)
composer require mallardduck/blade-lucide-icons
```

See all available icon packages at [Blade Icons](https://blade-ui-kit.com/blade-icons#icon-packages).

### Step 3: Register the plugin in your Panel

Add the plugin to your Filament panel provider:

```php
// app/Providers/Filament/AdminPanelProvider.php

use Wallacemartinss\FilamentIconPicker\FilamentIconPickerPlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        ->plugins([
            FilamentIconPickerPlugin::make(),
        ]);
}
```

### Step 4: Add package views to your Tailwind CSS configuration

Add the plugin's views to your theme CSS file so Tailwind can scan them:

```css
/* resources/css/filament/admin/theme.css */

@import '/vendor/filament/filament/resources/css/theme.css';

@source '../../../../vendor/wallacemartinss/filament-icon-picker/resources/views/**/*';
```

### Step 5: Build your assets

```bash
npm run build
```

### Step 6: (Optional) Publish the config file

```bash
php artisan vendor:publish --tag="filament-icon-picker-config"
```

### Step 7: Clear caches

```bash
php artisan optimize:clear
```

### Step 8: (Optional) Cache icons for better performance

```bash
php artisan icons:cache
```



## Usage

### Form Field

```php
use Wallacemartinss\FilamentIconPicker\Forms\Components\IconPicker;

public static function form(Form $form): Form
{
    return $form
        ->schema([
            IconPicker::make('icon')
                ->label('Select an Icon')
                ->required(),
        ]);
}
```

#### Restricting Icon Sets

```php
IconPicker::make('icon')
    ->allowedSets(['heroicons', 'fontawesome-solid', 'phosphor-icons'])
```

#### Custom Modal Size

```php
IconPicker::make('icon')
    ->modalSize('5xl') // sm, md, lg, xl, 2xl, 3xl, 4xl, 5xl, 6xl, 7xl
```

#### With Placeholder

```php
IconPicker::make('icon')
    ->placeholder('Choose an icon...')
```

#### Hide Set Filter

```php
IconPicker::make('icon')
    ->showSetFilter(false)
```

### Table Column

```php
use Wallacemartinss\FilamentIconPicker\Tables\Columns\IconColumn;

public static function table(Table $table): Table
{
    return $table
        ->columns([
            IconColumn::make('icon')
                ->label('Icon'),
        ]);
}
```

### Infolist Entry

```php
use Wallacemartinss\FilamentIconPicker\Infolists\Components\IconEntry;

public static function infolist(Infolist $infolist): Infolist
{
    return $infolist
        ->schema([
            IconEntry::make('icon')
                ->label('Icon'),
        ]);
}
```

## Configuration

The config file allows you to customize the picker behavior:

```php
// config/filament-icon-picker.php

return [
    /*
    |--------------------------------------------------------------------------
    | Allowed Icon Sets
    |--------------------------------------------------------------------------
    |
    | Define which icon sets should be available in the picker.
    | Leave empty array [] to allow all installed blade-icon sets.
    |
    | Important: Use the exact set name, not the package name!
    | Examples:
    |   - 'heroicons' (from blade-heroicons)
    |   - 'fontawesome-solid', 'fontawesome-regular', 'fontawesome-brands' (from blade-fontawesome)
    |   - 'phosphor-icons' (from blade-phosphor-icons)
    |   - 'google-material-design-icons' (from blade-google-material-design-icons)
    |
    */
    'allowed_sets' => [],

    /*
    |--------------------------------------------------------------------------
    | Icons Per Page
    |--------------------------------------------------------------------------
    |
    | Number of icons to load initially and on each scroll batch.
    | Increase for faster browsing, decrease for better performance.
    |
    */
    'icons_per_page' => 100,

    /*
    |--------------------------------------------------------------------------
    | Column Layout
    |--------------------------------------------------------------------------
    |
    | Number of columns in the icon grid for different screen sizes.
    |
    */
    'columns' => [
        'default' => 5,
        'sm' => 7,
        'md' => 9,
        'lg' => 10,
    ],

    /*
    |--------------------------------------------------------------------------
    | Modal Size
    |--------------------------------------------------------------------------
    |
    | The size of the icon picker modal.
    | Options: 'sm', 'md', 'lg', 'xl', '2xl', '3xl', '4xl', '5xl', '6xl', '7xl'
    |
    */
    'modal_size' => '4xl',

    /*
    |--------------------------------------------------------------------------
    | Cache Icons
    |--------------------------------------------------------------------------
    |
    | Whether to cache the icon list for better performance.
    | Set to false during development if you're adding new icons frequently.
    |
    */
    'cache_icons' => true,

    /*
    |--------------------------------------------------------------------------
    | Cache Duration
    |--------------------------------------------------------------------------
    |
    | How long to cache the icon list (in seconds).
    | Default: 86400 (24 hours)
    |
    */
    'cache_duration' => 86400,
];
```

## Finding Icon Set Names

To find the correct set names for your installed packages, run:

```bash
php artisan tinker
```

Then:

```php
use Wallacemartinss\FilamentIconPicker\IconSetManager;
$manager = new IconSetManager();
print_r($manager->getSetNames());
```

This will output all available set names like:

```
Array
(
    [0] => heroicons
    [1] => fontawesome-solid
    [2] => fontawesome-regular
    [3] => fontawesome-brands
    [4] => phosphor-icons
    [5] => google-material-design-icons
)
```

## Troubleshooting

### Icons not showing in the modal

1. Make sure you have at least one blade-icons package installed
2. Check that the set names in your config are correct (run the tinker command above)
3. Clear caches: `php artisan optimize:clear`

### Modal styling looks broken

1. Make sure you added the `@source` directive to your theme CSS
2. Rebuild assets: `npm run build`
3. Clear view cache: `php artisan view:clear`

### Infinite scroll not working

1. Clear browser cache with `Ctrl+Shift+R`
2. Check browser console for JavaScript errors

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Wallace Martins](https://github.com/wallacemartinss)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
