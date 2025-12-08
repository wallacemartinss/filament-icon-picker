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

        // Include SVG content for each icon to avoid individual HTTP requests
        $iconsWithSvg = $result['icons']->map(function ($icon) {
            $icon['svg'] = $this->getIconSvg($icon['name']);

            return $icon;
        });

        return response()->json([
            'icons' => $iconsWithSvg->toArray(),
            'has_more' => $result['hasMore'],
            'total' => $result['total'],
            'page' => $page,
        ]);
    }

    /**
     * Get the SVG content for an icon.
     */
    protected function getIconSvg(string $iconName): string
    {
        try {
            $svg = svg($iconName)->toHtml();

            // Add consistent sizing
            return preg_replace(
                '/<svg([^>]*)>/',
                '<svg$1 style="width: 1.5rem; height: 1.5rem;">',
                $svg
            ) ?: $svg;
        } catch (\Exception $e) {
            // Return a placeholder SVG
            return '<svg xmlns="http://www.w3.org/2000/svg" style="width: 1.5rem; height: 1.5rem;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>';
        }
    }
}
