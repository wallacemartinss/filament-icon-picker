@php
    $state = $getState();
    $sizeClasses = $getSizeClasses();
@endphp

<div class="filament-icon-column flex items-center">
    @if ($state)
        <x-filament::icon :icon="$state" :class="$sizeClasses . ' text-gray-700 dark:text-gray-200'" />
    @else
        <span class="text-gray-400">—</span>
    @endif
</div>
