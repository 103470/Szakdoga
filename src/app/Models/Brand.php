<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Brand extends Model
{
     use SoftDeletes;

    protected $fillable = ['name', 'slug', 'logo'];

    protected $hidden = [
        'updated_at',
        'deleted_at',
        'updated_by',
        'deleted_by',
        'created_at',
    ];

    
    protected static function booted()
    {
        static::creating(function ($brand) {
            if (empty($brand->slug)) {
                $brand->slug = Str::slug($brand->name);
            }
        });
    }
}
