<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use App\Models\SubCategory;
use App\Models\ProductCategory;
use App\Models\OemNumber;
use App\Models\PartVehicle;

class Product extends Model
{
    use SoftDeletes;

    protected $table = 'products';

    protected $fillable = [
        'subcategory_id',
        'product_category_id',
        'name',
        'slug',
        'article_number',
        'manufacturer',
        'description',
        'price',
        'currency',
        'stock',
        'image',
        'is_active',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
        'stock' => 'integer',
    ];

    public function subcategory()
    {
        return $this->belongsTo(SubCategory::class, 'subcategory_id', 'subcategory_id');
    }

    public function productCategory()
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }

    public function oemNumbers()
    {
        return $this->hasMany(OemNumber::class, 'product_id');
    }

    public function partVehicles()
    {
        return $this->hasManyThrough(
            PartVehicle::class,
            OemNumber::class,
            'product_id',     
            'oem_number_id',  
            'id',             
            'id'              
        );
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getFormattedPriceAttribute()
    {
        return number_format($this->price, 0, ',', ' ') . ' ' . $this->currency;
    }

    public function getUrlAttribute()
    {
        if ($this->productCategory && $this->subcategory && $this->subcategory->category) {
            return route('termekek', [
                $this->subcategory->category->slug,
                $this->subcategory->slug,
                $this->productCategory->slug
            ]);
        }

        if ($this->subcategory && $this->subcategory->category) {
            return route('termekkategoria', [
                $this->subcategory->category->slug,
                $this->subcategory->slug,
                $this->slug
            ]);
        }

        return route('termekek', ['slug' => $this->slug]);
    }


    protected static function booted()
    {
        static::creating(function ($product) {
            if (empty($product->slug) && !empty($product->article_number)) {
                $product->slug = Str::slug($product->article_number);
            }
        });

        static::updating(function ($product) {
            if (empty($product->slug) && !empty($product->article_number)) {
                $product->slug = Str::slug($product->article_number);
            }
        });
    }
}
