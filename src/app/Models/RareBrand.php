<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use App\Models\RareBrands\Type;

class RareBrand extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'slug'];

    protected static function booted()
    {
        static::creating(function ($rare_brand) {
            if (empty($rare_brand->slug)) {
                $rare_brand->slug = Str::slug($rare_brand->name);
            }
        });
    }

    public function types()
    {
        return $this->hasMany(Type::class);
    }

}
