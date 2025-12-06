<?php

declare(strict_types=1);

namespace Wallacemartinss\FilamentIconPicker\Facades;

use Illuminate\Support\Facades\Facade;
use Wallacemartinss\FilamentIconPicker\IconSetManager;

/**
 * @method static array getSets()
 * @method static array getSetNames()
 * @method static \Illuminate\Support\Collection getIcons(?array $allowedSets = null)
 * @method static \Illuminate\Support\Collection searchIcons(string $query, ?array $allowedSets = null, ?string $setFilter = null)
 * @method static array getIconsPaginated(int $page = 1, int $perPage = 100, ?string $search = null, ?string $setFilter = null, ?array $allowedSets = null)
 * @method static void clearCache()
 *
 * @see \Wallacemartinss\FilamentIconPicker\IconSetManager
 */
class IconPicker extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return IconSetManager::class;
    }
}
