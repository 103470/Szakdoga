<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Attributes\Test;

class AdminUserManagementTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function admin_can_list_users()
    {
        $admin = User::create([
            'firstname' => 'Admin',
            'lastname' => 'User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'is_admin' => true,
            'account_type' => 'personal',
            'phone_country_code' => '+36',
            'phone_number' => '301234567',

            // Billing
            'billing_country' => 'HU',
            'billing_zip' => '1137',
            'billing_city' => 'Budapest',
            'billing_street_name' => 'Fő',
            'billing_street_type' => 'utca',
            'billing_house_number' => '11',

            // Shipping
            'shipping_country' => 'HU',
            'shipping_zip' => '1137',
            'shipping_city' => 'Budapest',
            'shipping_street_name' => 'Fő',
            'shipping_street_type' => 'utca',
            'shipping_house_number' => '11',
        ]);

        $this->actingAs($admin);

        $response = $this->get('/admin/users');

        $response->assertStatus(200);
    }
}
