<?php

declare(strict_types=1);

namespace Wallacemartinss\FilamentIconPicker\Tests\Unit;

use PHPUnit\Framework\Attributes\Test;
use Wallacemartinss\FilamentIconPicker\IconSetManager;
use Wallacemartinss\FilamentIconPicker\Tests\TestCase;

final class IconSetManagerTest extends TestCase
{
    #[Test]
    public function it_can_be_instantiated(): void
    {
        $manager = new IconSetManager;

        $this->assertInstanceOf(IconSetManager::class, $manager);
    }

    #[Test]
    public function it_returns_array_of_set_names(): void
    {
        $manager = new IconSetManager;
        $setNames = $manager->getSetNames();

        $this->assertIsArray($setNames);
    }

    #[Test]
    public function it_returns_collection_of_icons(): void
    {
        $manager = new IconSetManager;
        $icons = $manager->getIcons();

        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $icons);
    }

    #[Test]
    public function it_can_search_icons(): void
    {
        $manager = new IconSetManager;
        $results = $manager->searchIcons('user');

        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $results);
    }

    #[Test]
    public function it_can_get_icons_for_set(): void
    {
        $manager = new IconSetManager;
        $setNames = $manager->getSetNames();

        if (count($setNames) > 0) {
            $icons = $manager->getIconsForSet($setNames[0]);
            $this->assertIsArray($icons);
        } else {
            $this->markTestSkipped('No icon sets installed');
        }
    }

    #[Test]
    public function it_returns_sets_with_metadata(): void
    {
        $manager = new IconSetManager;
        $sets = $manager->getSets();

        $this->assertIsArray($sets);

        if (count($sets) > 0) {
            $firstSet = array_values($sets)[0];
            $this->assertArrayHasKey('name', $firstSet);
            $this->assertArrayHasKey('prefix', $firstSet);
        }
    }
}
