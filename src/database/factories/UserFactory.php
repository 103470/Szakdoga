<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    public function definition(): array
    {
        return [
            'lastname' => $this->faker->lastName(),
            'firstname' => $this->faker->firstName(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => bcrypt('password123'),
            'remember_token' => Str::random(10),

            'account_type' => 'personal',
            'phone_country_code' => '+36',
            'phone_number' => '301234567',

            'billing_country' => 'Hungary',
            'billing_zip' => '1137',
            'billing_city' => 'Budapest',
            'billing_street_name' => 'Fő',
            'billing_street_type' => 'utca',
            'billing_house_number' => '12',

            'shipping_country' => 'Hungary',
            'shipping_zip' => '1137',
            'shipping_city' => 'Budapest',
            'shipping_street_name' => 'Fő',
            'shipping_street_type' => 'utca',
            'shipping_house_number' => '12',

            'is_admin' => false,
            'provider' => null,
            'provider_id' => null,
            'provider_token' => null,
            'avatar' => null,
        ];
    }
}
