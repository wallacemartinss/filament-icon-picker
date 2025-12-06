<?php

declare(strict_types=1);

namespace Wallacemartinss\FilamentIconPicker;

use Filament\Contracts\Plugin;
use Filament\Panel;

class FilamentIconPickerPlugin implements Plugin
{
    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        /** @var static $plugin */
        $plugin = filament(app(static::class)->getId());

        return $plugin;
    }

    public function getId(): string
    {
        return 'filament-icon-picker';
    }

    public function register(Panel $panel): void
    {
        //
    }

    public function boot(Panel $panel): void
    {
        //
    }
}
