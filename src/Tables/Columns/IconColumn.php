<?php

declare(strict_types=1);

namespace Wallacemartinss\FilamentIconPicker\Tables\Columns;

use Filament\Tables\Columns\Column;

class IconColumn extends Column
{
    protected string $view = 'filament-icon-picker::tables.columns.icon-column';

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
