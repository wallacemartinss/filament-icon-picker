<?php

declare(strict_types=1);

namespace Wallacemartinss\FilamentIconPicker\Tests;

use BladeUI\Icons\BladeIconsServiceProvider;
use Filament\FilamentServiceProvider;
use Filament\Forms\FormsServiceProvider;
use Filament\Infolists\InfolistsServiceProvider;
use Filament\Support\SupportServiceProvider;
use Filament\Tables\TablesServiceProvider;
use Livewire\LivewireServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use Wallacemartinss\FilamentIconPicker\FilamentIconPickerServiceProvider;

abstract class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app): array
    {
        return [
            LivewireServiceProvider::class,
            BladeIconsServiceProvider::class,
            SupportServiceProvider::class,
            FormsServiceProvider::class,
            TablesServiceProvider::class,
            InfolistsServiceProvider::class,
            FilamentServiceProvider::class,
            FilamentIconPickerServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
        config()->set('database.default', 'testing');

        config()->set('filament-icon-picker.allowed_sets', []);
        config()->set('filament-icon-picker.icons_per_page', 100);
        config()->set('filament-icon-picker.modal_size', '4xl');
        config()->set('filament-icon-picker.cache_icons', false);
    }
}
