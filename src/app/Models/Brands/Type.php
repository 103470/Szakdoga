<?php

namespace App\Models\Brands;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use App\Models\Brand;
use App\Models\Brands\Type;

class Type extends Model
{
    use SoftDeletes;

    protected $table = 'brand_types';

    protected $fillable = ['brand_id', 'name', 'slug'];

    protected $hidden = [
        'updated_at',
        'deleted_at',
        'updated_by',
        'deleted_by',
        'created_at',
    ];

    protected static function booted()
    {
        static::creating(function ($type) {
            if (empty($type->slug)) {
                $type->slug = Str::slug($type->name);
            }
        });
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function vintages()
    {
        return $this->hasMany(Vintage::class, 'type_id');
    }

}
