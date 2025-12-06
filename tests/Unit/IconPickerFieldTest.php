<?php

declare(strict_types=1);

namespace Wallacemartinss\FilamentIconPicker\Tests\Unit;

use PHPUnit\Framework\Attributes\Test;
use Wallacemartinss\FilamentIconPicker\Forms\Components\IconPickerField;
use Wallacemartinss\FilamentIconPicker\Tests\TestCase;

final class IconPickerFieldTest extends TestCase
{
    #[Test]
    public function it_can_be_instantiated(): void
    {
        $field = IconPickerField::make('icon');

        $this->assertInstanceOf(IconPickerField::class, $field);
        $this->assertEquals('icon', $field->getName());
    }

    #[Test]
    public function it_has_default_placeholder(): void
    {
        $field = IconPickerField::make('icon');

        $this->assertNotNull($field->getPlaceholder());
    }

    #[Test]
    public function it_can_set_custom_placeholder(): void
    {
        $field = IconPickerField::make('icon')
            ->placeholder('Choose an icon...');

        $this->assertEquals('Choose an icon...', $field->getPlaceholder());
    }

    #[Test]
    public function it_can_set_allowed_sets(): void
    {
        $field = IconPickerField::make('icon')
            ->allowedSets(['heroicons', 'fontawesome-solid']);

        $this->assertEquals(['heroicons', 'fontawesome-solid'], $field->getAllowedSets());
    }

    #[Test]
    public function it_is_searchable_by_default(): void
    {
        $field = IconPickerField::make('icon');

        $this->assertTrue($field->isSearchable());
    }

    #[Test]
    public function it_can_disable_search(): void
    {
        $field = IconPickerField::make('icon')
            ->searchable(false);

        $this->assertFalse($field->isSearchable());
    }

    #[Test]
    public function it_shows_set_filter_by_default(): void
    {
        $field = IconPickerField::make('icon');

        $this->assertTrue($field->shouldShowSetFilter());
    }

    #[Test]
    public function it_can_hide_set_filter(): void
    {
        $field = IconPickerField::make('icon')
            ->showSetFilter(false);

        $this->assertFalse($field->shouldShowSetFilter());
    }

    #[Test]
    public function it_can_set_modal_size(): void
    {
        $field = IconPickerField::make('icon')
            ->modalSize('5xl');

        $this->assertEquals('64rem', $field->getModalSize());
    }

    #[Test]
    public function it_has_default_modal_size(): void
    {
        $field = IconPickerField::make('icon');

        $this->assertEquals('56rem', $field->getModalSize());
    }

    #[Test]
    public function it_returns_correct_grid_columns(): void
    {
        $field = IconPickerField::make('icon');
        $columns = $field->getGridColumns();

        $this->assertIsArray($columns);
        $this->assertArrayHasKey('default', $columns);
    }
}
