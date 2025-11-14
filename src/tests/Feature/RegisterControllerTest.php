<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegisterControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register()
    {
        $data = [
            'lastname' => 'Kiss',
            'firstname' => 'Bence',
            'email' => 'bence@example.com',
            'password' => 'titok123',
            'password_confirmation' => 'titok123',
            'account_type' => 'personal',
            'phone_country_code' => '+36',
            'phone_number' => '302223344',
            'billing_country' => 'Magyarország',
            'billing_zip' => '1137',
            'billing_city' => 'Budapest',
            'billing_street_name' => 'Fő',
            'billing_street_type' => 'utca',
            'billing_house_number' => '12',
            'shipping_country' => 'Magyarország',
            'shipping_zip' => '1137',
            'shipping_city' => 'Budapest',
            'shipping_street_name' => 'Fő',
            'shipping_street_type' => 'utca',
            'shipping_house_number' => '12',
            'accept_tos' => 'on',
            'accept_privacy' => 'on',
        ];

        $response = $this->post('/register', $data);

        $response->assertRedirect('/');
        $this->assertDatabaseHas('users', ['email' => 'bence@example.com']);
        $this->assertAuthenticated();
    }
}
