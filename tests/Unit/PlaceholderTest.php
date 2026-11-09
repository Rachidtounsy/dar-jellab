<?php

namespace Tests\Unit;

use App\Support\Placeholder;
use PHPUnit\Framework\TestCase;

class PlaceholderTest extends TestCase
{
    public function test_it_renders_a_tinted_svg_for_each_kind(): void
    {
        foreach (['hood', 'caftan', 'cap', 'shoe'] as $kind) {
            $svg = Placeholder::svg('0F7B5F', $kind);

            $this->assertStringStartsWith('<svg', $svg);
            $this->assertStringContainsString('#0F7B5F', $svg);
        }
    }

    public function test_it_strips_unsafe_characters_from_the_colour(): void
    {
        $svg = Placeholder::svg('"><script>alert(1)</script>', 'hood');

        $this->assertStringNotContainsString('<script', $svg);
    }
}
