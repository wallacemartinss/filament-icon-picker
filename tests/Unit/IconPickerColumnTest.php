<?php

declare(strict_types=1);

namespace Wallacemartinss\FilamentIconPicker\Tests\Unit;

use PHPUnit\Framework\Attributes\Test;
use Wallacemartinss\FilamentIconPicker\Tables\Columns\IconPickerColumn;
use Wallacemartinss\FilamentIconPicker\Tests\TestCase;

final class IconPickerColumnTest extends TestCase
{
    #[Test]
    public function it_can_be_instantiated(): void
    {
        $column = IconPickerColumn::make('icon');

        $this->assertInstanceOf(IconPickerColumn::class, $column);
        $this->assertEquals('icon', $column->getName());
    }

    #[Test]
    public function it_has_default_size(): void
    {
        $column = IconPickerColumn::make('icon');

        $this->assertEquals('md', $column->getSize());
    }

    #[Test]
    public function it_can_set_size(): void
    {
        $column = IconPickerColumn::make('icon')->size('lg');

        $this->assertEquals('lg', $column->getSize());
    }

    #[Test]
    public function it_can_use_size_shortcuts(): void
    {
        $this->assertEquals('xs', IconPickerColumn::make('icon')->extraSmall()->getSize());
        $this->assertEquals('sm', IconPickerColumn::make('icon')->small()->getSize());
        $this->assertEquals('md', IconPickerColumn::make('icon')->medium()->getSize());
        $this->assertEquals('lg', IconPickerColumn::make('icon')->large()->getSize());
        $this->assertEquals('xl', IconPickerColumn::make('icon')->extraLarge()->getSize());
    }

    #[Test]
    public function it_returns_correct_size_classes(): void
    {
        $column = IconPickerColumn::make('icon');

        $this->assertEquals('w-4 h-4', $column->size('xs')->getSizeClasses());
        $this->assertEquals('w-5 h-5', $column->size('sm')->getSizeClasses());
        $this->assertEquals('w-6 h-6', $column->size('md')->getSizeClasses());
        $this->assertEquals('w-8 h-8', $column->size('lg')->getSizeClasses());
        $this->assertEquals('w-10 h-10', $column->size('xl')->getSizeClasses());
        $this->assertEquals('w-12 h-12', $column->size('2xl')->getSizeClasses());
    }

    #[Test]
    public function it_has_no_color_by_default(): void
    {
        $column = IconPickerColumn::make('icon');

        $this->assertNull($column->getColor());
        $this->assertNull($column->getColorStyle());
        $this->assertEquals('text-gray-700 dark:text-gray-200', $column->getColorClasses());
    }

    #[Test]
    public function it_can_set_color(): void
    {
        $column = IconPickerColumn::make('icon')->color('primary');

        $this->assertEquals('primary', $column->getColor());
    }

    #[Test]
    public function it_can_use_color_shortcuts(): void
    {
        $this->assertEquals('primary', IconPickerColumn::make('icon')->primary()->getColor());
        $this->assertEquals('gray', IconPickerColumn::make('icon')->secondary()->getColor());
        $this->assertEquals('success', IconPickerColumn::make('icon')->success()->getColor());
        $this->assertEquals('warning', IconPickerColumn::make('icon')->warning()->getColor());
        $this->assertEquals('danger', IconPickerColumn::make('icon')->danger()->getColor());
        $this->assertEquals('info', IconPickerColumn::make('icon')->info()->getColor());
    }

    #[Test]
    public function it_returns_correct_color_style(): void
    {
        $column = IconPickerColumn::make('icon');

        $this->assertStringContainsString('--primary-500', $column->color('primary')->getColorStyle());
        $this->assertStringContainsString('--success-500', $column->color('success')->getColorStyle());
        $this->assertStringContainsString('--warning-500', $column->color('warning')->getColorStyle());
        $this->assertStringContainsString('--danger-500', $column->color('danger')->getColorStyle());
        $this->assertStringContainsString('--info-500', $column->color('info')->getColorStyle());
    }

    #[Test]
    public function it_can_use_custom_color_classes(): void
    {
        $column = IconPickerColumn::make('icon')->color('text-purple-500');

        $this->assertEquals('text-purple-500', $column->getColorClasses());
    }

    #[Test]
    public function it_does_not_show_label_by_default(): void
    {
        $column = IconPickerColumn::make('icon');

        $this->assertFalse($column->shouldShowLabel());
    }

    #[Test]
    public function it_can_show_label(): void
    {
        $column = IconPickerColumn::make('icon')->showLabel();

        $this->assertTrue($column->shouldShowLabel());
    }

    #[Test]
    public function it_has_no_animation_by_default(): void
    {
        $column = IconPickerColumn::make('icon');

        $this->assertNull($column->getAnimation());
        $this->assertNull($column->getAnimationStyle());
    }

    #[Test]
    public function it_can_set_animation(): void
    {
        $column = IconPickerColumn::make('icon')->animation('spin');

        $this->assertEquals('spin', $column->getAnimation());
    }

    #[Test]
    public function it_can_use_animation_shortcuts(): void
    {
        $this->assertEquals('spin', IconPickerColumn::make('icon')->spin()->getAnimation());
        $this->assertEquals('pulse', IconPickerColumn::make('icon')->pulse()->getAnimation());
    }

    #[Test]
    public function it_returns_correct_animation_style(): void
    {
        $column = IconPickerColumn::make('icon');

        $this->assertStringContainsString('spin', $column->spin()->getAnimationStyle());
        $this->assertStringContainsString('pulse', $column->pulse()->getAnimationStyle());
    }

    #[Test]
    public function it_can_set_animation_speed(): void
    {
        $column = IconPickerColumn::make('icon')->spin()->animationSpeed('0.5s');

        $this->assertEquals('0.5s', $column->getAnimationSpeed());
        $this->assertStringContainsString('0.5s', $column->getAnimationStyle());
    }

    #[Test]
    public function it_can_set_speed_via_shortcut(): void
    {
        $spinColumn = IconPickerColumn::make('icon')->spin('0.3s');
        $this->assertEquals('spin', $spinColumn->getAnimation());
        $this->assertEquals('0.3s', $spinColumn->getAnimationSpeed());
        $this->assertStringContainsString('0.3s', $spinColumn->getAnimationStyle());

        $pulseColumn = IconPickerColumn::make('icon')->pulse('0.5s');
        $this->assertEquals('pulse', $pulseColumn->getAnimation());
        $this->assertEquals('0.5s', $pulseColumn->getAnimationSpeed());
        $this->assertStringContainsString('0.5s', $pulseColumn->getAnimationStyle());
    }

    #[Test]
    public function it_uses_default_speed_when_not_specified(): void
    {
        $spinColumn = IconPickerColumn::make('icon')->spin();
        $this->assertStringContainsString('1s', $spinColumn->getAnimationStyle());

        $pulseColumn = IconPickerColumn::make('icon')->pulse();
        $this->assertStringContainsString('2s', $pulseColumn->getAnimationStyle());
    }

    #[Test]
    public function it_can_combine_color_size_and_animation(): void
    {
        $column = IconPickerColumn::make('icon')
            ->success()
            ->large()
            ->spin();

        $this->assertEquals('success', $column->getColor());
        $this->assertEquals('lg', $column->getSize());
        $this->assertEquals('spin', $column->getAnimation());
        $this->assertStringContainsString('--success-500', $column->getColorStyle());
        $this->assertEquals('w-8 h-8', $column->getSizeClasses());
        $this->assertStringContainsString('spin', $column->getAnimationStyle());
    }

    #[Test]
    public function it_can_use_css_color_values(): void
    {
        $column = IconPickerColumn::make('icon')->color('#ff5500');
        $this->assertEquals('color: #ff5500;', $column->getColorStyle());

        $column2 = IconPickerColumn::make('icon')->color('rgb(255, 85, 0)');
        $this->assertEquals('color: rgb(255, 85, 0);', $column2->getColorStyle());

        $column3 = IconPickerColumn::make('icon')->color('purple');
        $this->assertEquals('color: purple;', $column3->getColorStyle());
    }
}
