<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use App\Models\Category;
use App\Models\FuelType;

class SubCategory extends Model
{
    use SoftDeletes;

    protected $table = 'subcategories';

    protected $primaryKey = 'subcategory_id';

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'fuel_type_id',
        'updated_by',
        'deleted_by',
    ];

    protected static function booted()
    {
        static::creating(function ($subcategory) {
            if (empty($subcategory->slug)) {
                $subcategory->slug = Str::slug($subcategory->name, '_');
            }
        });
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'kategory_id');
    }

    public function productCategories()
    {
        return $this->hasMany(ProductCategory::class, 'subcategory_id');
    }

    public function fuelType()
    {
        return $this->belongsTo(FuelType::class, 'fuel_type_id');
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }




}
