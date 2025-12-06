<?php

declare(strict_types=1);

namespace Wallacemartinss\FilamentIconPicker;

use BladeUI\Icons\Factory as IconFactory;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use ReflectionClass;

class IconSetManager
{
    protected IconFactory $iconFactory;

    protected ?Collection $cachedIcons = null;

    public function __construct()
    {
        $this->iconFactory = app(IconFactory::class);
    }

    /**
     * Get all available icon sets from blade-icons factory.
     *
     * @return array<string, array<string, mixed>>
     */
    public function getSets(): array
    {
        $sets = [];

        // Use reflection to access the protected sets property from the Factory
        $reflection = new ReflectionClass($this->iconFactory);

        if ($reflection->hasProperty('sets')) {
            $setsProperty = $reflection->getProperty('sets');
            $setsProperty->setAccessible(true);
            $factorySets = $setsProperty->getValue($this->iconFactory);

            foreach ($factorySets as $setName => $setConfig) {
                $sets[$setName] = [
                    'name' => $setName,
                    'prefix' => $setConfig['prefix'] ?? $setName,
                    'path' => $setConfig['path'] ?? $setConfig['paths'][0] ?? null,
                    'paths' => $setConfig['paths'] ?? [$setConfig['path'] ?? null],
                ];
            }
        }

        // Also check config as fallback
        $bladeIconsConfig = config('blade-icons.sets', []);
        foreach ($bladeIconsConfig as $setName => $setConfig) {
            if (! isset($sets[$setName])) {
                $sets[$setName] = [
                    'name' => $setName,
                    'prefix' => $setConfig['prefix'] ?? $setName,
                    'path' => $setConfig['path'] ?? null,
                    'paths' => [$setConfig['path'] ?? null],
                ];
            }
        }

        return $sets;
    }

    /**
     * Get available icon set names.
     *
     * @return array<string>
     */
    public function getSetNames(): array
    {
        return array_keys($this->getSets());
    }

    /**
     * Get all icons from all allowed sets.
     *
     * @param  array<string>|null  $allowedSets
     * @return Collection<int, array{name: string, set: string, prefix: string}>
     */
    public function getIcons(?array $allowedSets = null): Collection
    {
        $cacheKey = 'filament-icon-picker:icons:'.md5(serialize($allowedSets));

        if (config('filament-icon-picker.cache_icons', true)) {
            return Cache::remember(
                $cacheKey,
                config('filament-icon-picker.cache_duration', 86400),
                fn () => $this->loadIcons($allowedSets)
            );
        }

        return $this->loadIcons($allowedSets);
    }

    /**
     * Load icons from disk.
     *
     * @param  array<string>|null  $allowedSets
     * @return Collection<int, array{name: string, set: string, prefix: string}>
     */
    protected function loadIcons(?array $allowedSets = null): Collection
    {
        $icons = collect();
        $sets = $this->getSets();

        $configAllowedSets = config('filament-icon-picker.allowed_sets', []);
        if (! empty($configAllowedSets)) {
            $allowedSets = $allowedSets
                ? array_intersect($allowedSets, $configAllowedSets)
                : $configAllowedSets;
        }

        foreach ($sets as $setName => $setConfig) {
            if ($allowedSets && ! in_array($setName, $allowedSets)) {
                continue;
            }

            $setIcons = $this->getIconsFromSet($setName, $setConfig);
            $icons = $icons->merge($setIcons);
        }

        return $icons->sortBy('name')->values();
    }

    /**
     * Get icons from a specific set.
     *
     * @param  array<string, mixed>  $setConfig
     * @return Collection<int, array{name: string, set: string, prefix: string}>
     */
    protected function getIconsFromSet(string $setName, array $setConfig): Collection
    {
        $icons = collect();
        $prefix = $setConfig['prefix'] ?? $setName;
        $paths = $setConfig['paths'] ?? [$setConfig['path'] ?? null];

        foreach ($paths as $path) {
            if (! $path || ! File::isDirectory($path)) {
                continue;
            }

            $files = File::allFiles($path);

            foreach ($files as $file) {
                if ($file->getExtension() !== 'svg') {
                    continue;
                }

                $relativePath = str_replace($path.DIRECTORY_SEPARATOR, '', $file->getPathname());
                $iconName = str_replace([DIRECTORY_SEPARATOR, '/', '.svg'], ['-', '-', ''], $relativePath);
                $fullName = $prefix.'-'.$iconName;

                $icons->push([
                    'name' => $fullName,
                    'set' => $setName,
                    'prefix' => $prefix,
                    'label' => $this->formatIconLabel($iconName),
                ]);
            }
        }

        return $icons;
    }

    /**
     * Format icon name to a human-readable label.
     */
    protected function formatIconLabel(string $name): string
    {
        return ucwords(str_replace(['-', '_'], ' ', $name));
    }

    /**
     * Search icons by name.
     *
     * @param  array<string>|null  $allowedSets
     * @return Collection<int, array{name: string, set: string, prefix: string}>
     */
    public function searchIcons(string $query, ?array $allowedSets = null, ?string $setFilter = null): Collection
    {
        $icons = $this->getIcons($allowedSets);

        if ($setFilter) {
            $icons = $icons->filter(fn ($icon) => $icon['set'] === $setFilter);
        }

        if (empty($query)) {
            return $icons;
        }

        $query = strtolower($query);

        return $icons->filter(function ($icon) use ($query) {
            return str_contains(strtolower($icon['name']), $query) ||
                str_contains(strtolower($icon['label']), $query);
        })->values();
    }

    /**
     * Get icons paginated for lazy loading.
     *
     * @param  array<string>|null  $allowedSets
     * @return array{icons: Collection, hasMore: bool, total: int}
     */
    public function getIconsPaginated(
        int $page = 1,
        int $perPage = 100,
        ?string $search = null,
        ?string $setFilter = null,
        ?array $allowedSets = null
    ): array {
        $icons = $search
            ? $this->searchIcons($search, $allowedSets, $setFilter)
            : $this->getIcons($allowedSets);

        if ($setFilter && ! $search) {
            $icons = $icons->filter(fn ($icon) => $icon['set'] === $setFilter);
        }

        $total = $icons->count();
        $offset = ($page - 1) * $perPage;
        $paginatedIcons = $icons->slice($offset, $perPage)->values();

        return [
            'icons' => $paginatedIcons,
            'hasMore' => ($offset + $perPage) < $total,
            'total' => $total,
        ];
    }

    /**
     * Clear the icon cache.
     */
    public function clearCache(): void
    {
        Cache::forget('filament-icon-picker:icons:'.md5(serialize(null)));

        $sets = $this->getSetNames();
        foreach ($sets as $set) {
            Cache::forget('filament-icon-picker:icons:'.md5(serialize([$set])));
        }
    }

    /**
     * Get icons for a specific set.
     *
     * @return array<string>
     */
    public function getIconsForSet(string $setName): array
    {
        $sets = $this->getSets();

        if (! isset($sets[$setName])) {
            return [];
        }

        $setConfig = $sets[$setName];
        $icons = $this->getIconsFromSet($setName, $setConfig);

        return $icons->pluck('name')->toArray();
    }
}
