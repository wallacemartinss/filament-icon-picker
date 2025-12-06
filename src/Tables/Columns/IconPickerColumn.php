<?php

declare(strict_types=1);

namespace Wallacemartinss\FilamentIconPicker\Tables\Columns;

use Closure;
use Filament\Tables\Columns\Column;
use Wallacemartinss\FilamentIconPicker\Concerns\HasIconAnimation;
use Wallacemartinss\FilamentIconPicker\Concerns\HasIconColor;
use Wallacemartinss\FilamentIconPicker\Concerns\HasIconSize;

class IconPickerColumn extends Column
{
    use HasIconAnimation;
    use HasIconColor;
    use HasIconSize;

    protected string $view = 'filament-icon-picker::tables.columns.icon-column';

    protected bool|Closure $showLabel = false;

    public function showLabel(bool|Closure $show = true): static
    {
        $this->showLabel = $show;

        return $this;
    }

    public function shouldShowLabel(): bool
    {
        return (bool) $this->evaluate($this->showLabel);
    }
}
