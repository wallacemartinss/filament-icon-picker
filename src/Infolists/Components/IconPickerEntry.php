<?php

declare(strict_types=1);

namespace Wallacemartinss\FilamentIconPicker\Infolists\Components;

use Closure;
use Filament\Infolists\Components\Entry;
use Wallacemartinss\FilamentIconPicker\Concerns\HasIconAnimation;
use Wallacemartinss\FilamentIconPicker\Concerns\HasIconColor;
use Wallacemartinss\FilamentIconPicker\Concerns\HasIconSize;

class IconPickerEntry extends Entry
{
    use HasIconAnimation;
    use HasIconColor;
    use HasIconSize;

    protected string $view = 'filament-icon-picker::infolists.components.icon-entry';

    protected bool|Closure $showIconName = true;

    public function showIconName(bool|Closure $show = true): static
    {
        $this->showIconName = $show;

        return $this;
    }

    public function shouldShowIconName(): bool
    {
        return (bool) $this->evaluate($this->showIconName);
    }
}
