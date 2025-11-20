<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Brands\BrandModel;

class FuelType extends Model
{
    protected $fillable = [
        'name',
        'is_universal',
    ];


    public function brandModels()
    {
        return $this->hasMany(BrandModel::class);
    }

}
