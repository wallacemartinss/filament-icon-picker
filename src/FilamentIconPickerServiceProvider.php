<?php

declare(strict_types=1);

namespace Wallacemartinss\FilamentIconPicker;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Wallacemartinss\FilamentIconPicker\Commands\InstallIconsCommand;
use Wallacemartinss\FilamentIconPicker\Commands\PostInstallCommand;

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
                PostInstallCommand::class,
                InstallIconsCommand::class,
            ]);
    }

    public function packageBooted(): void
    {
        $this->app->singleton(IconSetManager::class, function () {
            return new IconSetManager();
        });
    }
}
