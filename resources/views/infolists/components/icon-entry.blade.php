@php
    $state = $getState();
    $sizeClasses = $getSizeClasses();
@endphp

<x-dynamic-component :component="$getEntryWrapperView()" :entry="$entry">
    <div class="filament-icon-entry flex items-center gap-2">
        @if ($state)
            <x-filament::icon :icon="$state" :class="$sizeClasses . ' text-gray-700 dark:text-gray-200'" />
            <span class="text-sm text-gray-600 dark:text-gray-400">{{ $state }}</span>
        @else
            <span class="text-gray-400">—</span>
        @endif
    </div>
</x-dynamic-component>
