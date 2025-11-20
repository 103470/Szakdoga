<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\OrderItem;
use App\Models\Address;
use App\Enums\OrderStatus;

class Order extends Model
{
   protected $fillable = [
        'user_id',
        'shipping_name',
        'shipping_email',
        'shipping_phone_prefix',
        'shipping_phone',
        'shipping_address_id',
        'shipping_city',
        'shipping_zip',
        'shipping_country',
        'billing_name',
        'billing_email',
        'billing_phone_prefix',
        'billing_phone',
        'billing_address_id',
        'billing_city',
        'billing_zip',
        'billing_country',
        'delivery_option',
        'payment_option',
        'order_number',
        'order_status',
    ];

    protected $casts = [
        'order_status' => OrderStatus::class,
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

    public function getTotalAttribute()
    {
        return $this->items->sum(function ($item) {
            return $item->price * $item->quantity;
        });
    }

}
