<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{

    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
    'lastname',
    'firstname',
    'email',
    'password',
    'account_type',
    'phone_country_code',
    'phone_number',
    'billing_country',
    'billing_zip',
    'billing_city',
    'billing_street_name',
    'billing_street_type',
    'billing_house_number',
    'billing_building',
    'billing_floor',
    'billing_door',
    'shipping_country',
    'shipping_zip',
    'shipping_city',
    'shipping_street_name',
    'shipping_street_type',
    'shipping_house_number',
    'shipping_building',
    'shipping_floor',
    'shipping_door',
    'profile_image',
    'is_admin',
];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $casts = [
    'email_verified_at' => 'datetime',
    'password' => 'hashed',
    'is_admin' => 'boolean',
    ];

}
