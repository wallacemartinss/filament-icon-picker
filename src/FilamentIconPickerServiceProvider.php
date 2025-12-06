<?php

declare(strict_types=1);

namespace Wallacemartinss\FilamentIconPicker;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Wallacemartinss\FilamentIconPicker\Commands\GenerateIconEnumsCommand;
use Wallacemartinss\FilamentIconPicker\Commands\InstallIconsCommand;

class FilamentIconPickerServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-icon-picker';

    public function configurePackage(Package $package): void
    {
        $package
            ->name(static::$name)
            ->hasConfigFile()
            ->hasViews()
            ->hasTranslations()
            ->hasRoute('web')
            ->hasCommands([
                InstallIconsCommand::class,
                GenerateIconEnumsCommand::class,
            ]);
    }

    public function packageBooted(): void
    {
        $this->app->singleton(IconSetManager::class, function () {
            return new IconSetManager;
        });
    }
}
