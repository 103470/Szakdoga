<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = [
        'country', 'zip', 'city', 'street_name', 'street_type',
        'house_number', 'building', 'floor', 'door', 
        'updated_by', 'deleted_by'
    ];

}
