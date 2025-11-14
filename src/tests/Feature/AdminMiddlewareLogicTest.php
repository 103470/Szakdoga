<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;

class AdminMiddlewareLogicTest extends TestCase
{
    public function test_admin_role_returns_true_if_user_is_admin()
    {
        $user = new User(['is_admin' => true]);

        $this->assertTrue($user->is_admin);
    }

    public function test_admin_role_returns_false_for_normal_user()
    {
        $user = new User(['is_admin' => false]);

        $this->assertFalse($user->is_admin);
    }
}
