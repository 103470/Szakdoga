<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;

class AdminFlagTest extends TestCase
{
    public function test_user_is_not_admin_by_default()
    {
        $user = User::factory()->make();

        $this->assertFalse($user->is_admin);
    }

    public function test_user_can_be_marked_as_admin()
    {
        $user = User::factory()->make(['is_admin' => true]);

        $this->assertTrue($user->is_admin);
    }
}
