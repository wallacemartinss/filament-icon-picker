<?php

declare(strict_types=1);

namespace Wallacemartinss\FilamentIconPicker\Tests\Unit;

use PHPUnit\Framework\Attributes\Test;
use Wallacemartinss\FilamentIconPicker\Infolists\Components\IconPickerEntry;
use Wallacemartinss\FilamentIconPicker\Tests\TestCase;

final class IconPickerEntryTest extends TestCase
{
    #[Test]
    public function it_can_be_instantiated(): void
    {
        $entry = IconPickerEntry::make('icon');

        $this->assertInstanceOf(IconPickerEntry::class, $entry);
        $this->assertEquals('icon', $entry->getName());
    }

    #[Test]
    public function it_has_default_size(): void
    {
        $entry = IconPickerEntry::make('icon');

        $this->assertEquals('md', $entry->getSize());
    }

    #[Test]
    public function it_can_set_size(): void
    {
        $entry = IconPickerEntry::make('icon')->size('lg');

        $this->assertEquals('lg', $entry->getSize());
    }

    #[Test]
    public function it_can_use_size_shortcuts(): void
    {
        $this->assertEquals('xs', IconPickerEntry::make('icon')->extraSmall()->getSize());
        $this->assertEquals('sm', IconPickerEntry::make('icon')->small()->getSize());
        $this->assertEquals('md', IconPickerEntry::make('icon')->medium()->getSize());
        $this->assertEquals('lg', IconPickerEntry::make('icon')->large()->getSize());
        $this->assertEquals('xl', IconPickerEntry::make('icon')->extraLarge()->getSize());
    }

    #[Test]
    public function it_returns_correct_size_classes(): void
    {
        $entry = IconPickerEntry::make('icon');

        $this->assertEquals('w-4 h-4', $entry->size('xs')->getSizeClasses());
        $this->assertEquals('w-5 h-5', $entry->size('sm')->getSizeClasses());
        $this->assertEquals('w-6 h-6', $entry->size('md')->getSizeClasses());
        $this->assertEquals('w-8 h-8', $entry->size('lg')->getSizeClasses());
        $this->assertEquals('w-10 h-10', $entry->size('xl')->getSizeClasses());
        $this->assertEquals('w-12 h-12', $entry->size('2xl')->getSizeClasses());
    }

    #[Test]
    public function it_has_no_color_by_default(): void
    {
        $entry = IconPickerEntry::make('icon');

        $this->assertNull($entry->getColor());
        $this->assertNull($entry->getColorStyle());
        $this->assertEquals('text-gray-700 dark:text-gray-200', $entry->getColorClasses());
    }

    #[Test]
    public function it_can_set_color(): void
    {
        $entry = IconPickerEntry::make('icon')->color('success');

        $this->assertEquals('success', $entry->getColor());
    }

    #[Test]
    public function it_can_use_color_shortcuts(): void
    {
        $this->assertEquals('primary', IconPickerEntry::make('icon')->primary()->getColor());
        $this->assertEquals('gray', IconPickerEntry::make('icon')->secondary()->getColor());
        $this->assertEquals('success', IconPickerEntry::make('icon')->success()->getColor());
        $this->assertEquals('warning', IconPickerEntry::make('icon')->warning()->getColor());
        $this->assertEquals('danger', IconPickerEntry::make('icon')->danger()->getColor());
        $this->assertEquals('info', IconPickerEntry::make('icon')->info()->getColor());
    }

    #[Test]
    public function it_returns_correct_color_style(): void
    {
        $entry = IconPickerEntry::make('icon');

        $this->assertStringContainsString('--primary-500', $entry->color('primary')->getColorStyle());
        $this->assertStringContainsString('--success-500', $entry->color('success')->getColorStyle());
        $this->assertStringContainsString('--warning-500', $entry->color('warning')->getColorStyle());
        $this->assertStringContainsString('--danger-500', $entry->color('danger')->getColorStyle());
        $this->assertStringContainsString('--info-500', $entry->color('info')->getColorStyle());
    }

    #[Test]
    public function it_can_use_custom_color_classes(): void
    {
        $entry = IconPickerEntry::make('icon')->color('text-indigo-600');

        $this->assertEquals('text-indigo-600', $entry->getColorClasses());
    }

    #[Test]
    public function it_shows_icon_name_by_default(): void
    {
        $entry = IconPickerEntry::make('icon');

        $this->assertTrue($entry->shouldShowIconName());
    }

    #[Test]
    public function it_can_hide_icon_name(): void
    {
        $entry = IconPickerEntry::make('icon')->showIconName(false);

        $this->assertFalse($entry->shouldShowIconName());
    }

    #[Test]
    public function it_has_no_animation_by_default(): void
    {
        $entry = IconPickerEntry::make('icon');

        $this->assertNull($entry->getAnimation());
        $this->assertNull($entry->getAnimationStyle());
    }

    #[Test]
    public function it_can_set_animation(): void
    {
        $entry = IconPickerEntry::make('icon')->animation('pulse');

        $this->assertEquals('pulse', $entry->getAnimation());
    }

    #[Test]
    public function it_can_use_animation_shortcuts(): void
    {
        $this->assertEquals('spin', IconPickerEntry::make('icon')->spin()->getAnimation());
        $this->assertEquals('pulse', IconPickerEntry::make('icon')->pulse()->getAnimation());
    }

    #[Test]
    public function it_returns_correct_animation_style(): void
    {
        $entry = IconPickerEntry::make('icon');

        $this->assertStringContainsString('spin', $entry->spin()->getAnimationStyle());
        $this->assertStringContainsString('pulse', $entry->pulse()->getAnimationStyle());
    }

    #[Test]
    public function it_can_set_animation_speed(): void
    {
        $entry = IconPickerEntry::make('icon')->pulse()->animationSpeed('1s');

        $this->assertEquals('1s', $entry->getAnimationSpeed());
        $this->assertStringContainsString('1s', $entry->getAnimationStyle());
    }

    #[Test]
    public function it_can_set_speed_via_shortcut(): void
    {
        $spinEntry = IconPickerEntry::make('icon')->spin('0.5s');
        $this->assertEquals('spin', $spinEntry->getAnimation());
        $this->assertEquals('0.5s', $spinEntry->getAnimationSpeed());
        $this->assertStringContainsString('0.5s', $spinEntry->getAnimationStyle());

        $pulseEntry = IconPickerEntry::make('icon')->pulse('1s');
        $this->assertEquals('pulse', $pulseEntry->getAnimation());
        $this->assertEquals('1s', $pulseEntry->getAnimationSpeed());
        $this->assertStringContainsString('1s', $pulseEntry->getAnimationStyle());
    }

    #[Test]
    public function it_uses_default_speed_when_not_specified(): void
    {
        $spinEntry = IconPickerEntry::make('icon')->spin();
        $this->assertStringContainsString('1s', $spinEntry->getAnimationStyle());

        $pulseEntry = IconPickerEntry::make('icon')->pulse();
        $this->assertStringContainsString('2s', $pulseEntry->getAnimationStyle());
    }

    #[Test]
    public function it_can_combine_color_size_and_animation(): void
    {
        $entry = IconPickerEntry::make('icon')
            ->danger()
            ->extraLarge()
            ->pulse();

        $this->assertEquals('danger', $entry->getColor());
        $this->assertEquals('xl', $entry->getSize());
        $this->assertEquals('pulse', $entry->getAnimation());
        $this->assertStringContainsString('--danger-500', $entry->getColorStyle());
        $this->assertEquals('w-10 h-10', $entry->getSizeClasses());
        $this->assertStringContainsString('pulse', $entry->getAnimationStyle());
    }

    #[Test]
    public function it_can_use_css_color_values(): void
    {
        $entry = IconPickerEntry::make('icon')->color('#00ff00');
        $this->assertEquals('color: #00ff00;', $entry->getColorStyle());

        $entry2 = IconPickerEntry::make('icon')->color('rgb(0, 255, 0)');
        $this->assertEquals('color: rgb(0, 255, 0);', $entry2->getColorStyle());

        $entry3 = IconPickerEntry::make('icon')->color('teal');
        $this->assertEquals('color: teal;', $entry3->getColorStyle());
    }
}
