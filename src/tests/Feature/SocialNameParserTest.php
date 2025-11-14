<?php

namespace Tests\Unit;

use Tests\TestCase;

class SocialNameParserTest extends TestCase
{
    public function test_fullname_splits_correctly()
    {
        $name = "John Doe";

        $parts = explode(' ', $name, 2);

        $this->assertEquals('John', $parts[0]);
        $this->assertEquals('Doe', $parts[1]);
    }

    public function test_single_name_still_returns_firstname()
    {
        $name = "Madonna";

        $parts = explode(' ', $name, 2);

        $this->assertEquals('Madonna', $parts[0]);
        $this->assertFalse(isset($parts[1]));
    }
}
