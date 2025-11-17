<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\CartItem;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
    'lastname',
    'firstname',
    'email',
    'password',
    'account_type',
    'phone_country_code',
    'phone_number',
    'billing_address_id',
    'shipping_address_id',
    'profile_image',
    'is_admin',
    'provider',
    'provider_id',
    'provider_token',
    'avatar',

];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
    'email_verified_at' => 'datetime',
    'password' => 'hashed',
    'is_admin' => 'boolean',
    ];
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }


}
