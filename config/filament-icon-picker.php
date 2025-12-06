<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Allowed Icon Sets
    |--------------------------------------------------------------------------
    |
    | Define which icon sets should be available in the picker.
    | Leave empty array to allow all installed blade-icon sets.
    |
    | Example: ['heroicons', 'tabler', 'fontawesome']
    |
    */
    'allowed_sets' => [],

    /*
    |--------------------------------------------------------------------------
    | Icons Per Page
    |--------------------------------------------------------------------------
    |
    | Number of icons to load initially and on each scroll batch.
    | Increase this value for faster browsing, decrease for better performance.
    |
    */
    'icons_per_page' => 100,

    /*
    |--------------------------------------------------------------------------
    | Column Layout
    |--------------------------------------------------------------------------
    |
    | Number of columns in the icon grid for different screen sizes.
    |
    */
    'columns' => [
        'default' => 6,
        'sm' => 8,
        'md' => 10,
        'lg' => 12,
    ],

    /*
    |--------------------------------------------------------------------------
    | Modal Size
    |--------------------------------------------------------------------------
    |
    | The size of the icon picker modal.
    | Options: 'sm', 'md', 'lg', 'xl', '2xl', '3xl', '4xl', '5xl', '6xl', '7xl'
    |
    */
    'modal_size' => '4xl',

    /*
    |--------------------------------------------------------------------------
    | Cache Icons
    |--------------------------------------------------------------------------
    |
    | Whether to cache the icon list for better performance.
    | Set to false during development if you're adding new icons frequently.
    |
    */
    'cache_icons' => true,

    /*
    |--------------------------------------------------------------------------
    | Cache Duration
    |--------------------------------------------------------------------------
    |
    | How long to cache the icon list (in seconds).
    | Default: 86400 (24 hours)
    |
    */
    'cache_duration' => 86400,
];
