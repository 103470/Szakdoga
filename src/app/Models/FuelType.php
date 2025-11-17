<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Brands\BrandModel;

class FuelType extends Model
{
    public function brandModels()
    {
        return $this->hasMany(BrandModel::class);
    }

}
