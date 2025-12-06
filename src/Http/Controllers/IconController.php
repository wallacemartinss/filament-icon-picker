<?php

declare(strict_types=1);

namespace Wallacemartinss\FilamentIconPicker\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Wallacemartinss\FilamentIconPicker\IconSetManager;

class IconController extends Controller
{
    public function __invoke(Request $request, IconSetManager $manager): JsonResponse
    {
        $page = (int) $request->get('page', 1);
        $perPage = (int) $request->get('per_page', config('filament-icon-picker.icons_per_page', 100));
        $search = $request->get('search');
        $setFilter = $request->get('set');
        $allowedSets = $request->get('allowed_sets');

        if (is_string($allowedSets)) {
            $allowedSets = explode(',', $allowedSets);
        }

        $result = $manager->getIconsPaginated(
            page: $page,
            perPage: $perPage,
            search: $search,
            setFilter: $setFilter,
            allowedSets: $allowedSets
        );

        return response()->json([
            'icons' => $result['icons']->toArray(),
            'has_more' => $result['hasMore'],
            'total' => $result['total'],
            'page' => $page,
        ]);
    }
}
