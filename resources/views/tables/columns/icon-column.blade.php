@php
    $state = $getState();
    $sizeClasses = $getSizeClasses();
    $colorClasses = $getColorClasses();
    $colorStyle = $getColorStyle();
    $animationStyle = $getAnimationStyle();
    $showLabel = $shouldShowLabel();

    // Combine styles
    $combinedStyle = trim(($colorStyle ?? '') . ' ' . ($animationStyle ?? ''));
@endphp

<!-- DEBUG: state={{ $state ?? 'NULL' }} size={{ $sizeClasses }} -->

<div class="filament-icon-column flex items-center gap-2">
    @if ($state)
        @if ($combinedStyle)
            <span style="{{ $combinedStyle }}">
                @svg($state, $sizeClasses)
            </span>
        @else
            <span class="{{ $colorClasses ?: 'text-gray-700 dark:text-gray-200' }}">
                @svg($state, $sizeClasses)
            </span>
        @endif
        @if ($showLabel)
            <span class="text-sm text-gray-600 dark:text-gray-400">{{ $state }}</span>
        @endif
    @else
        <span class="text-gray-400">—</span>
    @endif
</div>
