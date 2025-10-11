<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Product;
use App\Models\PartVehicle;

class OemNumber extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'product_id',
        'oem_number',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function partVehicles()
    {
        return $this->hasMany(PartVehicle::class, 'oem_number_id');
    }
}
