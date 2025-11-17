<?php

namespace App\Models\RareBrands;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\RareBrand;

class Type extends Model
{
    use softDeletes;

    protected $table = 'rarebrand_types';

    protected $fillable = [
        'name',
        'slug',
        'rare_brand_id',
        'updated_by',
        'deleted_by',
    ];

    public function rareBrand()
    {
        return $this->belongsTo(RareBrand::class, 'rare_brand_id');
    }

    protected static function booted()
    {
        static::creating(function ($type) {
            if (empty($type->slug)) {
                $type->slug = Str::slug($type->name);
            }
        });
    }
}
