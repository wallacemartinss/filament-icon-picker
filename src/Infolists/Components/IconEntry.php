<?php

declare(strict_types=1);

namespace Wallacemartinss\FilamentIconPicker\Infolists\Components;

use Filament\Infolists\Components\Entry;

class IconEntry extends Entry
{
    protected string $view = 'filament-icon-picker::infolists.components.icon-entry';

    protected string $size = 'md';

    public function size(string $size): static
    {
        $this->size = $size;

        return $this;
    }

    public function getSize(): string
    {
        return $this->size;
    }

    public function getSizeClasses(): string
    {
        return match ($this->size) {
            'xs' => 'w-4 h-4',
            'sm' => 'w-5 h-5',
            'md' => 'w-6 h-6',
            'lg' => 'w-8 h-8',
            'xl' => 'w-10 h-10',
            default => 'w-6 h-6',
        };
    }
}
