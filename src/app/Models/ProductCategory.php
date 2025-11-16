<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class ProductCategory extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'subcategory_id',
        'name',
        'slug',
        'updated_by',
        'deleted_by',
    ];

    protected static function booted()
    {
        static::creating(function ($productCategory) {
            if (empty($productCategory->slug)) {
                $productCategory->slug = Str::slug($productCategory->name, '_');
            }
        });
    }

    public function subcategory()
    {
        return $this->belongsTo(SubCategory::class, 'subcategory_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'product_category_id');
    }
}
