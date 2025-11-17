<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class LoginControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_login_with_valid_credentials()
    {
        // KÉZI felhasználó létrehozás (factory helyett)
        $user = User::create([
            'lastname' => 'Teszt',
            'firstname' => 'Elek',
            'email' => 'teszt@example.com',
            'password' => Hash::make('password123'),
            'account_type' => 'personal',
            'phone_country_code' => '+36',
            'phone_number' => '301234567',

            // billing
            'billing_country' => 'Hungary',
            'billing_zip' => '1137',
            'billing_city' => 'Budapest',
            'billing_street_name' => 'Fő',
            'billing_street_type' => 'utca',
            'billing_house_number' => '12',

            // shipping
            'shipping_country' => 'Hungary',
            'shipping_zip' => '1137',
            'shipping_city' => 'Budapest',
            'shipping_street_name' => 'Fő',
            'shipping_street_type' => 'utca',
            'shipping_house_number' => '12',

            'is_admin' => false,
        ]);

        // Bejelentkezési kérés
        $response = $this->post('/login', [
            'email' => 'teszt@example.com',
            'password' => 'password123'
        ]);

        // Átirányítás és authentikáció ellenőrzése
        $response->assertRedirect('/');
        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function user_cannot_login_with_invalid_credentials()
    {
        // NEM létező user email
        $response = $this->post('/login', [
            'email' => 'nemletezik@example.com',
            'password' => 'rosszjelszo'
        ]);

        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }
}

