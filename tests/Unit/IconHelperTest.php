<?php

declare(strict_types=1);

namespace Wallacemartinss\FilamentIconPicker\Tests\Unit;

use PHPUnit\Framework\Attributes\Test;
use Wallacemartinss\FilamentIconPicker\Enums\Icon;
use Wallacemartinss\FilamentIconPicker\Tests\TestCase;

final class IconHelperTest extends TestCase
{
    #[Test]
    public function it_generates_heroicon_outlined(): void
    {
        $this->assertEquals('heroicon-o-users', (string) Icon::heroicon('users'));
        $this->assertEquals('heroicon-o-users', (string) Icon::heroicon('users', 'outlined'));
    }

    #[Test]
    public function it_generates_heroicon_solid(): void
    {
        $this->assertEquals('heroicon-s-star', (string) Icon::heroicon('star', 'solid'));
    }

    #[Test]
    public function it_generates_heroicon_mini(): void
    {
        $this->assertEquals('heroicon-m-heart', (string) Icon::heroicon('heart', 'mini'));
    }

    #[Test]
    public function it_generates_heroicon_compact(): void
    {
        $this->assertEquals('heroicon-c-check', (string) Icon::heroicon('check', 'compact'));
    }

    #[Test]
    public function it_generates_fontawesome_solid(): void
    {
        $this->assertEquals('fas-heart', (string) Icon::fontawesome('heart'));
        $this->assertEquals('fas-heart', (string) Icon::fontawesome('heart', 'solid'));
    }

    #[Test]
    public function it_generates_fontawesome_regular(): void
    {
        $this->assertEquals('far-star', (string) Icon::fontawesome('star', 'regular'));
    }

    #[Test]
    public function it_generates_fontawesome_brands(): void
    {
        $this->assertEquals('fab-github', (string) Icon::fontawesome('github', 'brands'));
    }

    #[Test]
    public function it_generates_phosphor_icons(): void
    {
        $this->assertEquals('phosphor-user', (string) Icon::phosphor('user'));
    }

    #[Test]
    public function it_generates_phosphor_icons_with_variants(): void
    {
        $this->assertEquals('phosphor-user-bold', (string) Icon::phosphor('user', 'bold'));
        $this->assertEquals('phosphor-user-duotone', (string) Icon::phosphor('user', 'duotone'));
        $this->assertEquals('phosphor-user-fill', (string) Icon::phosphor('user', 'fill'));
        $this->assertEquals('phosphor-user-light', (string) Icon::phosphor('user', 'light'));
        $this->assertEquals('phosphor-user-thin', (string) Icon::phosphor('user', 'thin'));
    }

    #[Test]
    public function it_generates_material_icons(): void
    {
        $this->assertEquals('gmdi-account-circle', (string) Icon::material('account-circle'));
    }

    #[Test]
    public function it_generates_material_icons_with_variants(): void
    {
        $this->assertEquals('gmdi-account-circle-o', (string) Icon::material('account-circle', 'o'));
        $this->assertEquals('gmdi-account-circle-r', (string) Icon::material('account-circle', 'r'));
        $this->assertEquals('gmdi-account-circle-s', (string) Icon::material('account-circle', 's'));
        $this->assertEquals('gmdi-account-circle-tt', (string) Icon::material('account-circle', 'tt'));
    }

    #[Test]
    public function it_generates_tabler_icons(): void
    {
        $this->assertEquals('tabler-home', (string) Icon::tabler('home'));
    }

    #[Test]
    public function it_generates_lucide_icons(): void
    {
        $this->assertEquals('lucide-settings', (string) Icon::lucide('settings'));
    }

    #[Test]
    public function it_generates_bootstrap_icons(): void
    {
        $this->assertEquals('bi-house', (string) Icon::bootstrap('house'));
    }

    #[Test]
    public function it_can_create_icon_from_full_name(): void
    {
        $icon = Icon::make('heroicon-o-users');
        $this->assertEquals('heroicon-o-users', (string) $icon);
        $this->assertEquals('heroicons', $icon->getSet());
    }

    #[Test]
    public function it_returns_correct_set_name(): void
    {
        $this->assertEquals('heroicons', Icon::heroicon('users')->getSet());
        $this->assertEquals('google-material-design-icons', Icon::material('home')->getSet());
        $this->assertEquals('phosphor-icons', Icon::phosphor('heart')->getSet());
    }
}
