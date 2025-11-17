<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;

class UserFillableTest extends TestCase
{
    public function test_mass_assignment_sets_correct_fields()
    {
        $user = new User();

        $user->fill([
            'firstname' => 'Anna',
            'lastname' => 'Nagy',
            'email' => 'anna@example.com'
        ]);

        $this->assertEquals('Anna', $user->firstname);
        $this->assertEquals('Nagy', $user->lastname);
        $this->assertEquals('anna@example.com', $user->email);
    }
}
