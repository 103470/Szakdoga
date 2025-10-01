<?php

namespace App\Models\Brands;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Brands\Type;
use App\Models\Brands\Vintage;

class BrandModel extends Model
{
    use SoftDeletes;

    protected $table = 'brand_models';

    protected $fillable = [
        'type_id',
        'name',
        'ccm',
        'kw_hp',
        'engine_type',
        'year_from',
        'month_from',
        'year_to',
        'month_to',
        'frame',
        'updated_by',
        'deleted_by'
    ];

    public function type()
    {
        return $this->belongsTo(Type::class, 'type_id');
    }

    public function brand()
    {
        return $this->type->brand();
    }

    public function getYearRangeAttribute()
    {
        return sprintf(
            '%d/%02d - %d/%02d',
            $this->year_from,
            $this->month_from,
            $this->year_to,
            $this->month_to
        );
    }

    public function getCcmFormattedAttribute()
    {
        return $this->ccm ? $this->ccm . ' ccm' : null;
    }

    public function getKwLeFormattedAttribute()
    {
        if (!$this->kw_hp) return null;

        $parts = explode('/', $this->kw_hp);
        if (count($parts) === 2) {
            return $parts[0] . ' kW / ' . $parts[1] . ' LE';
        }

        return $this->kw_hp;
    }

    public function belongsToVintage(Vintage $vintage): bool
    {
        $yearMatch = $this->year_from <= $vintage->year_to && $this->year_to >= $vintage->year_from;
        $frameMatch = $vintage->frame ? $this->frame === $vintage->frame : true;

        return $yearMatch && $frameMatch;
    }

    public function scopeForVintage($query, Vintage $vintage)
    {
        return $query->where('type_id', $vintage->type_id)
                     ->where('year_from', '<=', $vintage->year_to)
                     ->where('year_to', '>=', $vintage->year_from)
                     ->when($vintage->frame, function ($q) use ($vintage) {
                         $q->where('frame', $vintage->frame);
                     });
    }

    public static function groupedByFuel($models)
    {
        $benzin = $models->filter(fn($m) => str_contains($m->name, 'Benzin'))->sortBy('name');
        $dizel  = $models->filter(fn($m) => str_contains($m->name, 'Dízel'))->sortBy('name');

        return [
            'Benzin' => $benzin,
            'Dízel'  => $dizel,
        ];
    }

    protected static function booted()
    {
        static::saving(function ($model) {
            if (empty($model->unique_code)) {
                $input = $model->name . '_' . $model->engine_type . '_' . $model->year_from . '_' . $model->year_to;

                if ($model->ccm) $input .= '_' . $model->ccm;
                if ($model->kw_hp) $input .= '_' . $model->kw_hp;

                $hash = abs(crc32($input));
                $model->unique_code = 1000 + ($hash % 9000000); 
            }

            if (empty($model->slug)) {
                $model->slug = Str::slug((string) $model->unique_code);
            }
        });
    
    }

}
