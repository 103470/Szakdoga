<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class UserModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_model_creates_correctly()
    {
        $user = User::factory()->create([
            'firstname' => 'Bence',
            'lastname' => 'Kiss',
            'email' => 'bence@example.com'
        ]);

        $this->assertEquals('Bence', $user->firstname);
        $this->assertDatabaseHas('users', [
            'email' => 'bence@example.com'
        ]);
    }

    public function test_password_is_hashed()
    {
        $user = User::factory()->create([
            'password' => Hash::make('titok123')
        ]);

        $this->assertTrue(Hash::check('titok123', $user->password));
    }
}
