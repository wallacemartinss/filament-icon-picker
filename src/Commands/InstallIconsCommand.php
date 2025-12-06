<?php

namespace Wallacemartinss\FilamentIconPicker\Commands;

use Illuminate\Console\Command;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\info;
use function Laravel\Prompts\multiselect;
use function Laravel\Prompts\note;
use function Laravel\Prompts\spin;
use function Laravel\Prompts\table;
use function Laravel\Prompts\warning;

class InstallIconsCommand extends Command
{
    protected $signature = 'filament-icon-picker:install-icons
                            {--all : Install all available icon packages}
                            {--list : List all available icon packages}';

    protected $description = 'Install icon packages for Filament Icon Picker';

    /**
     * @var array<string, array{package: string, description: string, icons: string, prefix: string}>
     */
    protected array $iconPackages = [
        'heroicons' => [
            'package' => 'blade-ui-kit/blade-heroicons',
            'description' => 'Heroicons by Tailwind CSS',
            'icons' => '~1,300',
            'prefix' => 'heroicon-*',
        ],
        'fontawesome' => [
            'package' => 'owenvoke/blade-fontawesome',
            'description' => 'Font Awesome (Solid, Regular, Brands)',
            'icons' => '~2,800',
            'prefix' => 'fas-*, far-*, fab-*',
        ],
        'phosphor' => [
            'package' => 'codeat3/blade-phosphor-icons',
            'description' => 'Phosphor Icons',
            'icons' => '~9,000',
            'prefix' => 'phosphor-*',
        ],
        'material' => [
            'package' => 'codeat3/blade-google-material-design-icons',
            'description' => 'Google Material Design',
            'icons' => '~10,000',
            'prefix' => 'gmdi-*',
        ],
        'tabler' => [
            'package' => 'blade-ui-kit/blade-tabler-icons',
            'description' => 'Tabler Icons',
            'icons' => '~4,400',
            'prefix' => 'tabler-*',
        ],
        'lucide' => [
            'package' => 'mallardduck/blade-lucide-icons',
            'description' => 'Lucide Icons',
            'icons' => '~1,400',
            'prefix' => 'lucide-*',
        ],
        'bootstrap' => [
            'package' => 'codeat3/blade-bootstrap-icons',
            'description' => 'Bootstrap Icons',
            'icons' => '~2,000',
            'prefix' => 'bi-*',
        ],
        'remix' => [
            'package' => 'codeat3/blade-remix-icon',
            'description' => 'Remix Icons',
            'icons' => '~2,800',
            'prefix' => 'remix-*',
        ],
    ];

    public function handle(): int
    {
        if ($this->option('list')) {
            return $this->listPackages();
        }

        if ($this->option('all')) {
            return $this->installAll();
        }

        return $this->interactiveInstall();
    }

    protected function listPackages(): int
    {
        $this->newLine();
        info('Available Icon Packages for Filament Icon Picker');
        $this->newLine();

        $rows = [];
        foreach ($this->iconPackages as $key => $info) {
            $installed = $this->isInstalled($info['package']);
            $rows[] = [
                ucfirst($key),
                $info['package'],
                $info['icons'],
                $info['prefix'],
                $installed ? '✅ Installed' : '❌ Not installed',
            ];
        }

        table(
            headers: ['Name', 'Package', 'Icons', 'Prefix', 'Status'],
            rows: $rows
        );

        $this->newLine();
        note('To install packages, run: php artisan filament-icon-picker:install-icons');

        return self::SUCCESS;
    }

    protected function installAll(): int
    {
        $packages = array_column($this->iconPackages, 'package');
        $packagesToInstall = array_filter($packages, fn ($p) => ! $this->isInstalled($p));

        if (empty($packagesToInstall)) {
            info('All icon packages are already installed!');

            return self::SUCCESS;
        }

        return $this->installPackages($packagesToInstall);
    }

    protected function interactiveInstall(): int
    {
        $this->newLine();
        info('🎨 Filament Icon Picker - Install Icons');
        $this->newLine();

        $options = [];
        $defaults = [];

        foreach ($this->iconPackages as $key => $info) {
            $installed = $this->isInstalled($info['package']);

            if ($installed) {
                continue;
            }

            $options[$info['package']] = sprintf(
                '%s - %s (%s icons)',
                ucfirst($key),
                $info['description'],
                $info['icons']
            );

            if ($key === 'heroicons') {
                $defaults[] = $info['package'];
            }
        }

        if (empty($options)) {
            info('All icon packages are already installed!');

            return self::SUCCESS;
        }

        $packages = multiselect(
            label: 'Select icon packages to install',
            options: $options,
            default: $defaults,
            hint: 'Space to select, Enter to confirm',
            scroll: 10
        );

        if (empty($packages)) {
            warning('No packages selected.');

            return self::SUCCESS;
        }

        if (! confirm('Install ' . count($packages) . ' package(s)?')) {
            return self::SUCCESS;
        }

        return $this->installPackages($packages);
    }

    protected function isInstalled(string $package): bool
    {
        $composerLock = base_path('composer.lock');

        if (! file_exists($composerLock)) {
            return false;
        }

        $content = file_get_contents($composerLock);

        return str_contains($content, '"name": "' . $package . '"');
    }

    /**
     * @param  array<string>  $packages
     */
    protected function installPackages(array $packages): int
    {
        $this->newLine();
        $failed = [];

        foreach ($packages as $package) {
            $result = spin(
                callback: function () use ($package) {
                    $output = [];
                    $code = 0;
                    exec("composer require {$package} --no-interaction 2>&1", $output, $code);

                    return $code;
                },
                message: "Installing {$package}..."
            );

            if ($result !== 0) {
                $failed[] = $package;
                $this->error("✗ Failed to install {$package}");
            } else {
                $this->info("✓ Installed {$package}");
            }
        }

        $this->newLine();

        if (! empty($failed)) {
            warning('Some packages failed to install. Try manually:');
            foreach ($failed as $package) {
                $this->line("  composer require {$package}");
            }

            return self::FAILURE;
        }

        info('✅ All packages installed successfully!');
        $this->newLine();
        note('Run "php artisan icons:cache" to cache icons for better performance.');

        return self::SUCCESS;
    }
}
