<?php

declare(strict_types=1);

namespace Wallacemartinss\FilamentIconPicker\Enums;

use Filament\Support\Contracts\ScalableIcon;
use Filament\Support\Enums\IconSize;
use Wallacemartinss\FilamentIconPicker\IconSetManager;

/**
 * Dynamic Icon helper that can represent any icon from any installed set.
 *
 * This class provides a fluent API for creating icon references without
 * needing to generate enums. It's perfect for dynamic icon usage.
 *
 * Usage:
 *   Icon::make('heroicon-o-users')
 *   Icon::heroicon('users', 'outlined')
 *   Icon::material('account-circle')
 *   Icon::phosphor('whatsapp-logo', 'duotone')
 */
class Icon implements ScalableIcon
{
    protected string $name;

    protected string $set;

    public function __construct(string $name, ?string $set = null)
    {
        $this->name = $name;
        $this->set = $set ?? $this->detectSet($name);
    }

    /**
     * Create an icon from its full name.
     */
    public static function make(string $name): static
    {
        return new static($name);
    }

    /**
     * Create a Heroicon.
     *
     * @param  string  $name  Icon name (e.g., 'users', 'star', 'home')
     * @param  string  $style  'outlined' (o), 'solid' (s), 'mini' (m), or 'compact' (c)
     */
    public static function heroicon(string $name, string $style = 'outlined'): static
    {
        $prefix = match ($style) {
            'outlined', 'o' => 'o',
            'solid', 's' => 's',
            'mini', 'm' => 'm',
            'compact', 'c' => 'c',
            default => 'o',
        };

        return new static("heroicon-{$prefix}-{$name}", 'heroicons');
    }

    /**
     * Create a Google Material Design icon.
     *
     * @param  string  $name  Icon name (e.g., 'account-circle', 'dashboard')
     * @param  string|null  $variant  null, 'o' (outlined), 'r' (round), 's' (sharp), 'tt' (two-tone)
     */
    public static function material(string $name, ?string $variant = null): static
    {
        $iconName = $variant ? "gmdi-{$name}-{$variant}" : "gmdi-{$name}";

        return new static($iconName, 'google-material-design-icons');
    }

    /**
     * Create a Phosphor icon.
     *
     * @param  string  $name  Icon name (e.g., 'whatsapp-logo', 'heart')
     * @param  string|null  $weight  null, 'bold', 'duotone', 'fill', 'light', 'thin'
     */
    public static function phosphor(string $name, ?string $weight = null): static
    {
        $iconName = $weight ? "phosphor-{$name}-{$weight}" : "phosphor-{$name}";

        return new static($iconName, 'phosphor-icons');
    }

    /**
     * Create a Font Awesome icon.
     *
     * @param  string  $name  Icon name (e.g., 'user', 'heart')
     * @param  string  $style  'solid' (fas), 'regular' (far), 'brands' (fab)
     */
    public static function fontawesome(string $name, string $style = 'solid'): static
    {
        $prefix = match ($style) {
            'solid', 'fas' => 'fas',
            'regular', 'far' => 'far',
            'brands', 'fab' => 'fab',
            default => 'fas',
        };

        return new static("{$prefix}-{$name}", "fontawesome-{$style}");
    }

    /**
     * Create a Tabler icon.
     */
    public static function tabler(string $name): static
    {
        return new static("tabler-{$name}", 'tabler');
    }

    /**
     * Create a Lucide icon.
     */
    public static function lucide(string $name): static
    {
        return new static("lucide-{$name}", 'lucide');
    }

    /**
     * Create a Bootstrap icon.
     */
    public static function bootstrap(string $name): static
    {
        return new static("bi-{$name}", 'bootstrap-icons');
    }

    /**
     * Get the icon name.
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get the icon set.
     */
    public function getSet(): string
    {
        return $this->set;
    }

    /**
     * Get the icon name for the given size (ScalableIcon interface).
     */
    public function getIconForSize(IconSize $size): string
    {
        return $this->name;
    }

    /**
     * Get the icon as a string.
     */
    public function __toString(): string
    {
        return $this->name;
    }

    /**
     * Detect the icon set from the icon name.
     */
    protected function detectSet(string $name): string
    {
        if (str_starts_with($name, 'heroicon-')) {
            return 'heroicons';
        }

        if (str_starts_with($name, 'gmdi-')) {
            return 'google-material-design-icons';
        }

        if (str_starts_with($name, 'phosphor-')) {
            return 'phosphor-icons';
        }

        if (str_starts_with($name, 'fas-') || str_starts_with($name, 'far-') || str_starts_with($name, 'fab-')) {
            return 'fontawesome';
        }

        if (str_starts_with($name, 'tabler-')) {
            return 'tabler';
        }

        if (str_starts_with($name, 'lucide-')) {
            return 'lucide';
        }

        if (str_starts_with($name, 'bi-')) {
            return 'bootstrap-icons';
        }

        return 'unknown';
    }

    /**
     * Search for icons by name.
     *
     * @return array<string>
     */
    public static function search(string $query, ?string $set = null): array
    {
        $manager = app(IconSetManager::class);
        $allowedSets = $set ? [$set] : null;
        $icons = $manager->searchIcons($query, $allowedSets);

        return $icons->pluck('name')->toArray();
    }

    /**
     * Get all available icons.
     *
     * @return array<string>
     */
    public static function all(?string $set = null): array
    {
        $manager = app(IconSetManager::class);
        $allowedSets = $set ? [$set] : null;
        $icons = $manager->getIcons($allowedSets);

        return $icons->pluck('name')->toArray();
    }
}
