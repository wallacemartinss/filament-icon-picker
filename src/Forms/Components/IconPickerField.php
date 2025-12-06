<?php

declare(strict_types=1);

namespace Wallacemartinss\FilamentIconPicker\Forms\Components;

use Closure;
use Filament\Forms\Components\Field;
use Wallacemartinss\FilamentIconPicker\IconSetManager;

class IconPickerField extends Field
{
    protected string $view = 'filament-icon-picker::forms.components.icon-picker';

    protected array|Closure|null $allowedSets = null;

    protected string|Closure|null $placeholder = null;

    protected bool|Closure $searchable = true;

    protected bool|Closure $showSetFilter = true;

    protected string|Closure|null $modalSize = null;

    protected function setUp(): void
    {
        parent::setUp();

        $this->placeholder(fn (): string => __('filament-icon-picker::icon-picker.placeholder'));
    }

    public function modalSize(string|Closure|null $size): static
    {
        $this->modalSize = $size;

        return $this;
    }

    public function getModalSize(): string
    {
        $size = $this->evaluate($this->modalSize) ?? config('filament-icon-picker.modal_size', '4xl');

        // Using inline max-width values to avoid Tailwind purging issues
        $sizes = [
            'sm' => '24rem',    // 384px
            'md' => '28rem',    // 448px
            'lg' => '32rem',    // 512px
            'xl' => '36rem',    // 576px
            '2xl' => '42rem',   // 672px
            '3xl' => '48rem',   // 768px
            '4xl' => '56rem',   // 896px
            '5xl' => '64rem',   // 1024px
            '6xl' => '72rem',   // 1152px
            '7xl' => '80rem',   // 1280px
            'full' => '100%',
        ];

        return $sizes[$size] ?? '56rem';
    }

    public function getGridColumns(): array
    {
        return config('filament-icon-picker.columns', [
            'default' => 6,
            'sm' => 8,
            'md' => 10,
            'lg' => 12,
        ]);
    }

    public function allowedSets(array|Closure|null $sets): static
    {
        $this->allowedSets = $sets;

        return $this;
    }

    public function getAllowedSets(): ?array
    {
        $componentSets = $this->evaluate($this->allowedSets);
        $configSets = config('filament-icon-picker.allowed_sets', []);

        // If component has specific sets, use those (intersected with config if config is set)
        if (is_array($componentSets) && ! empty($componentSets)) {
            if (! empty($configSets)) {
                return array_values(array_intersect($componentSets, $configSets));
            }

            return $componentSets;
        }

        // Otherwise use config sets
        if (! empty($configSets)) {
            return $configSets;
        }

        return null;
    }

    public function placeholder(string|Closure|null $placeholder): static
    {
        $this->placeholder = $placeholder;

        return $this;
    }

    public function getPlaceholder(): ?string
    {
        return $this->evaluate($this->placeholder);
    }

    public function searchable(bool|Closure $searchable = true): static
    {
        $this->searchable = $searchable;

        return $this;
    }

    public function isSearchable(): bool
    {
        return (bool) $this->evaluate($this->searchable);
    }

    public function showSetFilter(bool|Closure $show = true): static
    {
        $this->showSetFilter = $show;

        return $this;
    }

    public function shouldShowSetFilter(): bool
    {
        return (bool) $this->evaluate($this->showSetFilter);
    }

    public function getAvailableSets(): array
    {
        $manager = app(IconSetManager::class);
        $allowedSets = $this->getAllowedSets();

        $allSets = $manager->getSetNames();

        if ($allowedSets) {
            return array_values(array_intersect($allSets, $allowedSets));
        }

        return $allSets;
    }

    public function getIconsEndpoint(): string
    {
        return route('filament-icon-picker.icons');
    }
}
