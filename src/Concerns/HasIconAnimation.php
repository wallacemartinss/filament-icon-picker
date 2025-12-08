<?php

declare(strict_types=1);

namespace Wallacemartinss\FilamentIconPicker\Concerns;

use Closure;

trait HasIconAnimation
{
    protected string|Closure|null $animation = null;

    protected string|Closure|null $animationSpeed = null;

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
     * Set the animation speed.
     *
     * @param  string|Closure|null  $speed  Duration in CSS format (e.g., '0.5s', '2s', '500ms')
     */
    public function animationSpeed(string|Closure|null $speed): static
    {
        $this->animationSpeed = $speed;

        return $this;
    }

    /**
     * Apply spin animation (rotation).
     *
     * @param  string|null  $speed  Optional speed (e.g., '0.5s' for fast, '2s' for slow)
     */
    public function spin(?string $speed = null): static
    {
        $this->animation = 'spin';

        if ($speed !== null) {
            $this->animationSpeed = $speed;
        }

        return $this;
    }

    /**
     * Apply pulse animation (pulsing/fading).
     *
     * @param  string|null  $speed  Optional speed (e.g., '0.5s' for fast, '4s' for slow)
     */
    public function pulse(?string $speed = null): static
    {
        $this->animation = 'pulse';

        if ($speed !== null) {
            $this->animationSpeed = $speed;
        }

        return $this;
    }

    public function getAnimation(): ?string
    {
        return $this->evaluate($this->animation);
    }

    public function getAnimationSpeed(): ?string
    {
        return $this->evaluate($this->animationSpeed);
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

        $speed = $this->getAnimationSpeed();

        return match ($animation) {
            'spin' => 'animation: spin '.($speed ?? '1s').' linear infinite;',
            'pulse' => 'animation: pulse '.($speed ?? '2s').' cubic-bezier(0.4, 0, 0.6, 1) infinite;',
            default => null,
        };
    }
}
