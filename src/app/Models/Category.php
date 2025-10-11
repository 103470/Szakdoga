<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use App\Models\SubCategory;

class Category extends Model
{
    use SoftDeletes;

    protected $table = 'categories';

    protected $primaryKey = 'kategory_id';

    protected $fillable = [
        'name',
        'slug',
        'icon',
        'updated_by',
        'deleted_by',
    ];

    protected static function booted()
    {
    static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name, '_'); 
            }
        });

    }

    public function subcategories()
    {
        return $this->hasMany(Subcategory::class, 'category_id');
    }
}
