<?php

declare(strict_types=1);

namespace Wallacemartinss\FilamentIconPicker\Concerns;

use Closure;

trait HasIconAnimation
{
    protected string|Closure|null $animation = null;

    /**
     * Set the icon animation.
     *
     * Supports: spin, pulse
     */
    public function animation(string|Closure|null $animation): static
    {
        $this->animation = $animation;

        return $this;
    }

    /**
     * Apply spin animation (rotation).
     */
    public function spin(): static
    {
        return $this->animation('spin');
    }

    /**
     * Apply pulse animation (pulsing/fading).
     */
    public function pulse(): static
    {
        return $this->animation('pulse');
    }

    public function getAnimation(): ?string
    {
        return $this->evaluate($this->animation);
    }

    /**
     * Get inline CSS style for animation.
     * This ensures animations work without requiring Tailwind to compile the classes.
     */
    public function getAnimationStyle(): ?string
    {
        $animation = $this->getAnimation();

        if ($animation === null) {
            return null;
        }

        return match ($animation) {
            'spin' => 'animation: spin 1s linear infinite;',
            'pulse' => 'animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;',
            default => null,
        };
    }
}
