<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SocialController extends Controller
{
    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function callback($provider)
    {

        $socialUser = Socialite::driver($provider)->user();

        $user = User::where('provider', $provider)
                ->where('provider_id', $socialUser->getId())
                ->first();

        if (!$user) {
        $user = User::where('email', $socialUser->getEmail())->first();}

        if ($user) {
            $user->update([
                'provider' => $provider,
                'provider_id' => $socialUser->getId(),
            ]);
        } else {

            $fullName = $socialUser->getName() ?? $socialUser->getNickname() ?? '';
            $parts = explode(' ', $fullName, 2);

            $firstname = $parts[0] ?? '';
            $lastname = $parts[1] ?? '';

            $user = User::create([
                'firstname' => $firstname,
                'lastname' => $lastname,
                'email' => $socialUser->getEmail(),
                'password' => Str::random(16),
                'provider' => $provider,
                'provider_id' => $socialUser->getId(),
                'account_type' => 'user',
                'phone_country_code' => '',
                'phone_number' => '',
                'billing_country' => '',
                'billing_zip' => '',
                'billing_city' => '',
                'billing_street_name' => '',
                'billing_street_type' => '',
                'billing_house_number' => '',
                'billing_building' => null,
                'billing_floor' => null,
                'billing_door' => null,
                'shipping_country' => '',
                'shipping_zip' => '',
                'shipping_city' => '',
                'shipping_street_name' => '',
                'shipping_street_type' => '',
                'shipping_house_number' => '',
                'shipping_building' => null,
                'shipping_floor' => null,
                'shipping_door' => null,
                'profile_image' => $socialUser->getAvatar() ?? null,
                'is_admin' => false,
                
            ]);
        }
    
        Auth::login($user);

        return redirect('/');
    }
}