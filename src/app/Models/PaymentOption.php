<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentOption extends Model
{
    protected $fillable = [
        'name',
        'fee',
        'description',
        'is_active',
    ];
}
