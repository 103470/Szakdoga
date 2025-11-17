<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\OrderItem;
use App\Models\Address;

class Order extends Model
{
    protected $fillable = [
        'user_id', 'shipping_name', 'shipping_phone', 'shipping_address',
        'shipping_city', 'shipping_zip', 'shipping_country',
        'billing_name', 'billing_email', 'billing_phone',
        'billing_address',
        'billing_city', 'billing_zip', 'billing_country',
        'delivery_option', 'payment_option'
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function shippingAddress()
    {
        return $this->belongsTo(Address::class, 'shipping_address_id');
    }

    public function billingAddress()
    {
        return $this->belongsTo(Address::class, 'billing_address_id');
    }
}
