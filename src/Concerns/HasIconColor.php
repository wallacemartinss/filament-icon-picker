<?php

declare(strict_types=1);

namespace Wallacemartinss\FilamentIconPicker\Concerns;

use Closure;

trait HasIconColor
{
    protected string|Closure|null $color = null;

    /**
     * Set the icon color.
     *
     * Supports:
     * - Filament semantic colors: primary, secondary, success, warning, danger, info, gray
     * - CSS color values: #ff0000, rgb(255,0,0), purple, etc.
     * - Tailwind classes: text-purple-500 (must be included in your CSS)
     */
    public function color(string|Closure|null $color): static
    {
        $this->color = $color;

        return $this;
    }

    public function primary(): static
    {
        return $this->color('primary');
    }

    public function secondary(): static
    {
        return $this->color('gray');
    }

    public function success(): static
    {
        return $this->color('success');
    }

    public function warning(): static
    {
        return $this->color('warning');
    }

    public function danger(): static
    {
        return $this->color('danger');
    }

    public function info(): static
    {
        return $this->color('info');
    }

    public function getColor(): ?string
    {
        return $this->evaluate($this->color);
    }

    public function getColorStyle(): ?string
    {
        $color = $this->getColor();

        if ($color === null) {
            return null;
        }

        // Filament semantic colors using CSS variables
        $semanticColors = [
            'primary' => 'color: var(--primary-500);',
            'secondary' => 'color: var(--gray-500);',
            'gray' => 'color: var(--gray-500);',
            'success' => 'color: var(--success-500);',
            'warning' => 'color: var(--warning-500);',
            'danger' => 'color: var(--danger-500);',
            'info' => 'color: var(--info-500);',
        ];

        if (isset($semanticColors[$color])) {
            return $semanticColors[$color];
        }

        // Check if it's a CSS color value (hex, rgb, named color, etc.)
        // Not a Tailwind class (doesn't start with text-)
        if (! str_starts_with($color, 'text-')) {
            return "color: {$color};";
        }

        // It's a Tailwind class - return null so getColorClasses() handles it
        return null;
    }

    public function getColorClasses(): string
    {
        $color = $this->getColor();

        if ($color === null) {
            return 'text-gray-700 dark:text-gray-200';
        }

        // Semantic colors are handled by getColorStyle()
        $semanticColors = ['primary', 'secondary', 'gray', 'success', 'warning', 'danger', 'info'];
        if (in_array($color, $semanticColors)) {
            return '';
        }

        // If it starts with text-, it's a Tailwind class
        if (str_starts_with($color, 'text-')) {
            return $color;
        }

        // CSS color values are handled by getColorStyle()
        return '';
    }
}
