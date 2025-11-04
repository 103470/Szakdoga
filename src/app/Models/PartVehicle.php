<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\OemNumber;
use App\Models\Brands\BrandModel;
use App\Models\RareBrands\RareBrandModel;

class PartVehicle extends Model
{
    use SoftDeletes;

    protected $table = 'part_vehicle';

    protected $fillable = [
        'oem_number_id',
        'unique_code',
    ];

    public function oemNumber()
    {
        return $this->belongsTo(OemNumber::class, 'oem_number_id');
    }

    public function brandModel()
    {
        return $this->belongsTo(BrandModel::class, 'unique_code', 'unique_code');
    }

    public function rareBrandModel()
    {
        return $this->belongsTo(RareBrandModel::class, 'unique_code', 'unique_code');
    }

    public function getModelAttribute()
    {
        return $this->model_source === 'brand'
            ? $this->brandModel
            : $this->rareBrandModel;
    }
    

}
