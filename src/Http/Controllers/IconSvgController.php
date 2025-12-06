<?php

declare(strict_types=1);

namespace Wallacemartinss\FilamentIconPicker\Http\Controllers;

use BladeUI\Icons\Factory as IconFactory;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class IconSvgController extends Controller
{
    public function __invoke(string $icon, IconFactory $factory): Response
    {
        try {
            $svg = svg($icon)->toHtml();

            // Add width and height attributes to ensure proper sizing
            $svg = preg_replace(
                '/<svg([^>]*)>/',
                '<svg$1 style="width: 1.5rem; height: 1.5rem;">',
                $svg
            );

            return response($svg, 200, [
                'Content-Type' => 'image/svg+xml',
                'Cache-Control' => 'public, max-age=31536000',
            ]);
        } catch (\Exception $e) {
            // Return a placeholder SVG
            $placeholder = '<svg xmlns="http://www.w3.org/2000/svg" style="width: 1.5rem; height: 1.5rem;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>';

            return response($placeholder, 200, [
                'Content-Type' => 'image/svg+xml',
            ]);
        }
    }
}
