<?php

declare(strict_types=1);

namespace Wallacemartinss\FilamentIconPicker\Concerns;

use Closure;

trait HasIconSize
{
    protected string|Closure $size = 'md';

    public function size(string|Closure $size): static
    {
        $this->size = $size;

        return $this;
    }

    public function extraSmall(): static
    {
        return $this->size('xs');
    }

    public function small(): static
    {
        return $this->size('sm');
    }

    public function medium(): static
    {
        return $this->size('md');
    }

    public function large(): static
    {
        return $this->size('lg');
    }

    public function extraLarge(): static
    {
        return $this->size('xl');
    }

    public function getSize(): string
    {
        return $this->evaluate($this->size);
    }

    public function getSizeClasses(): string
    {
        return match ($this->getSize()) {
            'xs' => 'w-4 h-4',
            'sm' => 'w-5 h-5',
            'md' => 'w-6 h-6',
            'lg' => 'w-8 h-8',
            'xl' => 'w-10 h-10',
            '2xl' => 'w-12 h-12',
            default => 'w-6 h-6',
        };
    }
}
