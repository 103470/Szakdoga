<?php

namespace App\Models\Brands;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use App\Models\Brands\Type;

class Vintage extends Model
{
    use SoftDeletes;

    protected $table = 'brand_vintages';

    protected $fillable = [
        'type_id',
        'name',
        'slug',
        'frame',
        'year_from',
        'month_from',
        'year_to',
        'month_to',
        'updated_by',
        'deleted_by'
    ]; 

    public function type()
    {
        return $this->belongsTo(Type::class, 'type_id');
    }

    public function getTypeNameAttribute()
    {
        return $this->type ? $this->type->name : null;
    }

    public function brand()
    {
        return $this->type->brand();
    }

    public function getVintageRangeAttribute()
    {
        return sprintf(
            '%d/%02d - %d/%02d',
            $this->year_from,
            $this->month_from,
            $this->year_to,
            $this->month_to
        );
    }


    protected static function booted()
    {
        static::creating(function ($vintage) {
            if (empty($vintage->slug)) {
                $parts = [];

                 if (!empty($vintage->name)) {
                $parts[] = Str::slug($vintage->name, '_');
                }

                if (!empty($vintage->frame)) {
                $parts[] = Str::slug($vintage->frame, '_');
                }

                $parts[] = $vintage->year_from . '_' .
                       str_pad($vintage->month_from, 2, '0', STR_PAD_LEFT) . '_' .
                       $vintage->year_to . '_' .
                       str_pad($vintage->month_to, 2, '0', STR_PAD_LEFT);

                $vintage->slug = strtolower(implode('_', $parts));
            }
        });
    }

}
