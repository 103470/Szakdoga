<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Support\Facades\Hash;

class PasswordResetUnitTest extends TestCase
{
    public function test_password_hashing_and_verification()
    {
        $password = 'titkos123';
        $hash = Hash::make($password);

        $this->assertTrue(Hash::check($password, $hash));
    }
}
