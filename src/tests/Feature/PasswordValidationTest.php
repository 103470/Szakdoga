<?php

namespace Tests\Unit;

use Tests\TestCase;

class PasswordValidationTest extends TestCase
{
    public function test_passwords_match()
    {
        $password = 'titok123';
        $confirmation = 'titok123';

        $this->assertEquals($password, $confirmation);
    }

    public function test_password_minimum_length()
    {
        $password = 'abcdef'; // 6 karakter, ami a minimum

        $this->assertTrue(strlen($password) >= 6);
    }
}
