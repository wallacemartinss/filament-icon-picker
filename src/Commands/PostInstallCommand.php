<?php

namespace Wallacemartinss\FilamentIconPicker\Commands;

use Illuminate\Console\Command;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\info;
use function Laravel\Prompts\multiselect;
use function Laravel\Prompts\note;
use function Laravel\Prompts\spin;
use function Laravel\Prompts\warning;

class PostInstallCommand extends Command
{
    protected $signature = 'filament-icon-picker:post-install
                            {--skip-icons : Skip icon package installation}
                            {--all : Install all icon packages}';

    protected $description = 'Filament Icon Picker post-installation setup';

    /**
     * @var array<string, array{package: string, description: string, icons: string}>
     */
    protected array $iconPackages = [
        'heroicons' => [
            'package' => 'blade-ui-kit/blade-heroicons',
            'description' => 'Heroicons by Tailwind CSS team',
            'icons' => '~1,300 icons',
        ],
        'fontawesome' => [
            'package' => 'owenvoke/blade-fontawesome',
            'description' => 'Font Awesome icons (Solid, Regular, Brands)',
            'icons' => '~2,800 icons',
        ],
        'phosphor' => [
            'package' => 'codeat3/blade-phosphor-icons',
            'description' => 'Phosphor Icons - flexible icon family',
            'icons' => '~9,000 icons',
        ],
        'material' => [
            'package' => 'codeat3/blade-google-material-design-icons',
            'description' => 'Google Material Design Icons',
            'icons' => '~10,000 icons',
        ],
        'tabler' => [
            'package' => 'blade-ui-kit/blade-tabler-icons',
            'description' => 'Tabler Icons - clean and consistent',
            'icons' => '~4,400 icons',
        ],
        'lucide' => [
            'package' => 'mallardduck/blade-lucide-icons',
            'description' => 'Lucide Icons - beautiful & consistent',
            'icons' => '~1,400 icons',
        ],
        'bootstrap' => [
            'package' => 'codeat3/blade-bootstrap-icons',
            'description' => 'Bootstrap Icons',
            'icons' => '~2,000 icons',
        ],
        'remix' => [
            'package' => 'codeat3/blade-remix-icon',
            'description' => 'Remix Icons - neutral style',
            'icons' => '~2,800 icons',
        ],
    ];

    public function handle(): int
    {
        $this->displayBanner();

        if ($this->option('skip-icons')) {
            info('Icon package installation skipped.');

            return self::SUCCESS;
        }

        if (! $this->shouldInstallIcons()) {
            return self::SUCCESS;
        }

        $packages = $this->selectPackages();

        if (empty($packages)) {
            warning('No icon packages selected. You can install them later manually.');

            return self::SUCCESS;
        }

        return $this->installPackages($packages);
    }

    protected function displayBanner(): void
    {
        $this->newLine();
        $this->line('╔══════════════════════════════════════════════════════════════╗');
        $this->line('║                                                              ║');
        $this->line('║   🎨  <fg=cyan;options=bold>Filament Icon Picker</> - Post Installation              ║');
        $this->line('║                                                              ║');
        $this->line('║   A beautiful icon picker for Filament v4                    ║');
        $this->line('║   by Wallace Martins                                         ║');
        $this->line('║                                                              ║');
        $this->line('╚══════════════════════════════════════════════════════════════╝');
        $this->newLine();
    }

    protected function shouldInstallIcons(): bool
    {
        if (! $this->input->isInteractive()) {
            return false;
        }

        return confirm(
            label: 'Would you like to install icon packages now?',
            default: true,
            hint: 'You need at least one icon package to use the Icon Picker'
        );
    }

    /**
     * @return array<string>
     */
    protected function selectPackages(): array
    {
        if ($this->option('all')) {
            return array_column($this->iconPackages, 'package');
        }

        note('Select the icon packages you want to install:');
        $this->newLine();

        $options = [];
        foreach ($this->iconPackages as $key => $info) {
            $options[$info['package']] = sprintf(
                '%s - %s (%s)',
                ucfirst($key),
                $info['description'],
                $info['icons']
            );
        }

        return multiselect(
            label: 'Icon Packages',
            options: $options,
            default: ['blade-ui-kit/blade-heroicons'],
            hint: 'Space to select, Enter to confirm',
            scroll: 10
        );
    }

    /**
     * @param  array<string>  $packages
     */
    protected function installPackages(array $packages): int
    {
        $this->newLine();
        info('Installing ' . count($packages) . ' icon package(s)...');
        $this->newLine();

        $failed = [];

        foreach ($packages as $package) {
            $result = spin(
                callback: function () use ($package) {
                    $output = [];
                    $code = 0;
                    exec("composer require {$package} --no-interaction 2>&1", $output, $code);

                    return [
                        'code' => $code,
                        'output' => implode("\n", $output),
                    ];
                },
                message: "Installing {$package}..."
            );

            if ($result['code'] !== 0) {
                $failed[] = $package;
                $this->error("Failed to install {$package}");
            } else {
                $this->info("✓ Installed {$package}");
            }
        }

        $this->newLine();

        if (! empty($failed)) {
            warning('Some packages failed to install:');
            foreach ($failed as $package) {
                $this->line("  - {$package}");
            }
            $this->newLine();
            $this->line('You can try installing them manually:');
            foreach ($failed as $package) {
                $this->line("  composer require {$package}");
            }

            return self::FAILURE;
        }

        $this->displaySuccessMessage(count($packages));

        return self::SUCCESS;
    }

    protected function displaySuccessMessage(int $count): void
    {
        $this->newLine();
        $this->line('╔══════════════════════════════════════════════════════════════╗');
        $this->line('║                                                              ║');
        $this->line('║   ✅  <fg=green;options=bold>Installation Complete!</>                                  ║');
        $this->line('║                                                              ║');
        $this->line("║   {$count} icon package(s) installed successfully.               ║");
        $this->line('║                                                              ║');
        $this->line('║   <fg=yellow>Next steps:</>                                               ║');
        $this->line('║   1. Register the plugin in your PanelProvider               ║');
        $this->line('║   2. Add the views path to your Tailwind config              ║');
        $this->line('║   3. Run: npm run build                                      ║');
        $this->line('║                                                              ║');
        $this->line('║   📖 Docs: https://github.com/wallacemartinss/filament-icon-picker');
        $this->line('║                                                              ║');
        $this->line('╚══════════════════════════════════════════════════════════════╝');
        $this->newLine();
    }
}
